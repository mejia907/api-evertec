<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\orders;

class OrderController extends Controller
{

    private $seed;
    private $secretKey;
    private $login;
    private $nonce;
    private $nonceBase64;
    private $tranKey;
    private $response;

    public function __construct(){

        $this->response=[
            'status' => 'ok',
            'result' => array()
        ];
    }

    /**
     * Login for API
     *
     */
    public function loginApi(){

        $this->seed =  date('c');
        $this->secretKey = \Config('api.api_key');;
        $this->login =  \Config('api.api_login');

        if (function_exists('random_bytes')) {
            $this->nonce = bin2hex(random_bytes(16));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $this->nonce = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $this->nonce = mt_rand();
        }
        
        $this->nonceBase64 = base64_encode($this->nonce);
        $this->tranKey = base64_encode(sha1($this->nonce . $this->seed . $this->secretKey, true));
    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = orders::All();
        return $orders;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderForIdApi(Request $request)
    {
        $orders = orders::findOrFail($request->id);

        $this->loginApi();

        $response = Http::accept('application/json')->post('https://dev.placetopay.com/redirection/api/session/'.$orders->request_id, [
            'locale' => "es_CO",
            'auth' =>   [
                'login' => $this->login,
                'tranKey' => $this->tranKey,
                'nonce' =>  $this->nonceBase64,
                'seed' => $this->seed
            ],
        ]);
        return $response->body();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderForId(Request $request)
    {
        $rslt = $this->response;
        $orders = orders::findOrFail($request->id);
        
        $rslt["result"] = $orders;

        return $rslt;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rslt = $this->response;
        
        $orders = new orders();
        $orders->custumer_ducument_type = $request->custumer_ducument_type;
        $orders->custumer_ducument = $request->custumer_ducument;
        $orders->custumer_name = $request->custumer_name;
        $orders->custumer_email = $request->custumer_email;
        $orders->custumer_mobile = $request->custumer_mobile;
        $orders->status = $request->status;
              
        if($orders->save()){
            $responseHttp = $this->createSessionPay($orders);
            $rslt["result"] = $responseHttp;
            $this->updateTransaction($orders->id, $responseHttp->requestId, $responseHttp->processUrl);
        }else{
            $rslt["status"] = "error";
            $rslt["result"] = "Error al guardar el pedido";
        }

        return $rslt;
    }

    private function createSessionPay($orders){
    
        $this->loginApi();

        $response = Http::accept('application/json')->post('https://dev.placetopay.com/redirection/api/session/', [
            'locale' => "es_CO",
            'auth' =>   [
                'login' => $this->login,
                'tranKey' => $this->tranKey,
                'nonce' =>  $this->nonceBase64,
                'seed' => $this->seed
            ],
            'buyer' => [
                'document' => $orders->custumer_ducument, 
                'documentType' => $orders->custumer_ducument_type, 
                'name'=> $orders->custumer_name,
                'email'=> $orders->custumer_email,
            ],
            'payment' => [
                'reference' => '1122334455',
                'description' => 'Prueba',
                'amount' => [
                    'currency' => 'COP',
                    'total' => 3500000
                ],
                "allowPartial" => false
            ],
            "expiration" => "2022-12-30T00:00:00-05:00",
            "returnUrl" => "http://localhost:4200/status-order/".$orders->id,
            "ipAddress" => "127.0.0.1",
            "userAgent" => "Andres Felipe Mejia R."
        ]);

        return json_decode($response->body());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $orders = orders::findOrFail($request->id);
        $orders->custumer_name = $request->custumer_name;
        $orders->custumer_email = $request->custumer_email;
        $orders->custumer_mobile = $request->custumer_mobile;
        $orders->status = $request->status;

        $orders->save();

        return $orders;
    }

    /**
     * Update status for order
     * @param  int  $id
     * @param  string  $status
     */
    public function updateStatus(Request $request){
        $orders = orders::findOrFail($request->id);
        $orders->status = $request->status;

        $orders->save();

        return $orders;
    }

    /**
     * Update request id the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id 
     * @param  int  $request_id 
     */
    public function updateTransaction($id, $request_id, $process_url){
        
        $orders = orders::findOrFail($id);
        $orders->request_id = $request_id;
        $orders->process_url = $process_url;

        $orders->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $orders = orders::destroy($request->id);

        return $orders;
    }
}
