<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


## WebCheckout con API REST

Api que permite la integracion con el WebCheckout de PlacetoPAy.

Laravel Framework 8.83.16

## Instalación

Despues de clonar el repositorio se deben instalar las dependencias ejecutando el siguiente comando:


    $ composer install
    
En la consoloa de MYSQL cree una base de datos con el siguiente comando:


    $ mysqli -u root -p
    $ create database evertec

Posteriormente cree el database schema ejecutanto las migraciones:

    $ php artisan migrate


## Configuración

Los datos de conexión de la base de datos se encuentran en el archivo `.env` al igual que los datos de autenticación del web service.


## Ejecución del servidor

Para iniciar el servidor se ejecuta el siguiente comando:


    $ php artisan serve

