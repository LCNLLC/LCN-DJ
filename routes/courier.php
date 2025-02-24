<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courier\DashboardController;
use App\Http\Controllers\Courier\OrderController;
use App\Http\Controllers\Courier\InvoiceController;
use App\Http\Controllers\ShippingCostSizeController;
use App\Http\Controllers\Courier\ProfileController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\Courier\CourierController;
use App\Http\Controllers\Courier\PaymentController;
use App\Http\Controllers\Courier\CourierWithdrawRequestController;
use App\Http\Controllers\Courier\SupportTicketController;

// Route::group(['prefix' => 'courier', 'middleware' => ['courier', 'verified', 'prevent-back-history'], 'as' => 'courier.'], function 
Route::group(['prefix' => 'courier', 'middleware' => ['verified', 'prevent-back-history'], 'as' => 'courier.'], function() {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
    });

    Route::resource('orders', OrderController::class);

     Route::controller(OrderController::class)->group(function () {
        Route::post('/upload-delivery-proof/', 'delivery_proof')->name('orders.update_delivery_proof');
        Route::post('/orders/update_tracking_code', 'update_tracking_code')->name('orders.update_tracking_code');
    });

    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice/{order_id}', 'invoice_download')->name('invoice.download');
        Route::get('/print-invoice/{order_id}', 'invoice_print')->name('invoice.print');
    });

     Route::controller(ShippingCostSizeController::class)->group(function () {
        Route::get('/courier/shipping/cost/size', 'showShippingDetailsToCourier')->name('shipping.cost.size');
    });

         // Profile Settings
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::post('/profile/update/{id}', 'update')->name('profile.update');
    });

     Route::controller(AizUploadController::class)->group(function () {
        Route::any('/uploads', 'index')->name('uploaded-files.index');
        Route::any('/uploads/create', 'create')->name('uploads.create');
        Route::any('/uploads/file-info', 'file_info')->name('my_uploads.info');
        Route::get('/uploads/destroy/{id}', 'destroy')->name('my_uploads.destroy');
        Route::post('/bulk-uploaded-files-delete', 'bulk_uploaded_files_delete')->name('bulk-uploaded-files-delete');
    });

    Route::controller(CourierController::class)->group(function () {
        Route::get('/vehicle', 'index')->name('vehicle.index');
        Route::post('/vehicle/update', 'update')->name('vehicle.update');
    });

    Route::resource('payments', PaymentController::class);

    Route::controller(CourierWithdrawRequestController::class)->group(function () {
        Route::get('/money-withdraw-requests', 'index')->name('money_withdraw_requests.index');
        Route::post('/money-withdraw-request/store', 'store')->name('money_withdraw_request.store');
    });
    Route::controller(SupportTicketController::class)->group(function () {
        Route::get('/support_ticket', 'index')->name('support_ticket.index');
        Route::post('/support_ticket/store', 'store')->name('support_ticket.store');
        Route::get('/support_ticket/show/{id}', 'show')->name('support_ticket.show');
        Route::post('/support_ticket/reply', 'ticket_reply_store')->name('support_ticket.reply_store');
    });
});
