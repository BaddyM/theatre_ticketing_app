<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketingController;

Route::get('/',[TicketingController::class,'index'])->name("home");
Route::post('/add_customer',[TicketingController::class,'add_customer'])->name("add.customer");
Route::post('/customer_data',[TicketingController::class,'customer_data'])->name("customer.data");
Route::post('/customer_info',[TicketingController::class,'customer_info'])->name("customer.qrcode");
Route::get('/customer_qrcode/{seat_no}',[TicketingController::class,'display_customer_qrcode_info'])->name("customer.qrcode.display");
Route::post('/customer_clear',[TicketingController::class,'clear_seat'])->name("customer.seat.clear");