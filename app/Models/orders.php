<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    use HasFactory;
    protected $fillable =[
        'custumer_ducument_type',
        'custumer_ducument',
        'custumer_name',
        'custumer_email',
        'custumer_mobile',
        'status',
        'request_id',
        'process_url'
    ];
}
