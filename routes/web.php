<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['prefix' => 'api', 'middleware' => 'auth'], function () {

    Route::get('/user/rol', 'UserController@selectRol');
    Route::get('/dashboard', 'DashboardController'); //invoke
    Route::get('/notificacion/get', 'NotificacionController@get');

    Route::middleware(['roles:Administrador'])->group(function(){

        Route::get('/rol', 'RolController@index')->name('rol');
        Route::get('/rol/selectactivo', 'RolController@selectactivo');

        Route::get('/user', 'UserController@index')->name('user');
        Route::post('/user', 'UserController@store');
        Route::put('/user/{id}', 'UserController@update');
        Route::patch('/user/{id}/activar', 'UserController@active');



    });

    Route::middleware(['roles:Vendedor,Administrador'])->group(function(){

        Route::get('/cliente', 'ClienteController@index')->name('cliente');
        Route::post('/cliente', 'ClienteController@store');
        Route::put('/cliente/{id}', 'ClienteController@update');
        Route::get('/cliente/selectactivo', 'ClienteController@selectActivo');

        Route::get('/venta', 'VentaController@index');
        Route::post('/venta', 'VentaController@store');
        Route::patch('/venta/{id}/desactive', 'VentaController@desactive');
        Route::get('/venta/show/{id}', 'VentaController@show');
        Route::get('/venta/comprobante/{id}', 'ReporteController@rptComprobanteVenta');

    });


    Route::middleware(['roles:Almacenero,Administrador'])->group(function(){
        Route::get('/categoria', 'CategoriaController@index')->name('categoria');
        Route::post('/categoria', 'CategoriaController@store');
        Route::put('/categoria/{id}', 'CategoriaController@update');
        Route::patch('/categoria/{id}/activar', 'CategoriaController@active');
        Route::get('/categoria/selectactivo', 'CategoriaController@selectActivo');

        Route::get('/articulo', 'ArticuloController@index')->name('articulo');
        Route::post('/articulo', 'ArticuloController@store');
        Route::put('/articulo/{id}', 'ArticuloController@update');
        Route::patch('/articulo/{id}/activar', 'ArticuloController@active');
        Route::get('/articulo/buscar', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listar', 'ArticuloController@listar');
        Route::get('/articulo/buscarventa', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarventa', 'ArticuloController@listarVenta');
        Route::get('/articulo/reporte', 'ReporteController@rptArticulo');

        Route::get('/proveedor', 'ProveedorController@index')->name('proveedor');
        Route::post('/proveedor', 'ProveedorController@store');
        Route::put('/proveedor/{id}', 'ProveedorController@update');
        Route::get('/proveedor/selectactivo', 'ProveedorController@selectActivo');


        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso', 'IngresoController@store');
        Route::patch('/ingreso/{id}/desactive', 'IngresoController@desactive');
        Route::get('/ingreso/show/{id}', 'IngresoController@show');

    });

});



Route::middleware(['auth:web'])->group(function(){
    Route::get('/testsoap', 'HomeController@testSoap');

    //Route::get('/', 'HomeController@index')->where('any', '.*');
    Route::get('/{any}', 'HomeController@index')->where('any', '.*');
    //Route::get('/home', 'HomeController@index')->name('home');

});




