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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/register','EmpleadoController@register');
Route::post('/api/login','EmpleadoController@login');
Route::resource('/api/clientes','ClienteController');
Route::resource('/api/empleado','EmpleadoController');
Route::resource('/api/factura','FacturaController');
Route::resource('/api/producto','ProductoController');
Route::resource('/api/proveedores','ProveedorController');
Route::resource('/api/detalleFactura','DetalleFacturaController');
