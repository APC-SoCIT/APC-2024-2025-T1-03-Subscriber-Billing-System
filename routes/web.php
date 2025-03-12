<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\advancePaymentController;
use App\Http\Controllers\ChangePlanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PayPalController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'loginPost'])->name('login.submit');

    Route::get('/', [LoginController::class, 'guestHome'])->name('guestHome');
});

Route::group(['middleware' => ['custom.auth']], function () {
    Route::get('/home', [LoginController::class, 'home'])->name('home');
    Route::get('/subscriptions', [LoginController::class, 'subscriptions'])->name('subscriptions');
    Route::get('/subscriptions-change', [LoginController::class, 'changeSub'])->name('changeSub');
    Route::get('/account', [LoginController::class, 'account'])->name('account');
    Route::get('/accountPage', [LoginController::class, 'accountPage'])->name('accountPage');
    Route::post('/accountPage-post', [LoginController::class, 'accountPagePost'])->name('accountPagePost');
    Route::get('/payment', [LoginController::class, 'payment'])->name('payment');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


    Route::get('/account/download-statement', [LoginController::class, 'downloadStatement'])->name('account.download');

    // PayPal Payment
    Route::post('/paypal/payment', [PayPalController::class, 'createPayment'])->name('paypal.payment');
    Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
    Route::get('/receipt', [PayPalController::class, 'receipt'])->name('receipt');
    
    
    //Paypal for Changing Subscription
    Route::get('/changePlan-payment', [LoginController::class, 'changePlanPayment'])->name('changePlanPayment');
    Route::post('/paypal/changePlan-payment', [ChangePlanController::class, 'createPayment'])->name('ChangePaypal.payment');
    Route::get('/paypal/changePlan-success', [ChangePlanController::class, 'success'])->name('ChangePaypal.success');
    Route::get('/paypal/changePlan-cancel', [ChangePlanController::class, 'cancel'])->name('ChangePaypal.cancel');
    Route::get('/change_receipt', [ChangePlanController::class, 'changeReceipt'])->name('changeReceipt');

    //Advance payment paypal
    Route::get('/advancePayment-payment', [LoginController::class, 'advancePayment'])->name('advancePayment');
    Route::post('/paypal/advance-payment', [advancePaymentController::class, 'createPayment'])->name('advancePayment.payment');
    Route::get('/paypal/advance-success', [advancePaymentController::class, 'success'])->name('advancePayment.success');
    Route::get('/paypal/advance-cancel', [advancePaymentController::class, 'cancel'])->name('advancePayment.cancel');
});

Route::group(['middleware' => ['admin.auth']], function () {
    Route::get('/admin-logout', [LoginController::class, 'adminLogout'])->name('adminLogout');
    Route::get('/admin-home', [LoginController::class, 'adminHome'])->name('admin.home');
    Route::get('/admin-subscription', [AdminController::class, 'adminSubscription'])->name('adminSubscription');
    Route::get('/admin-user', [AdminController::class, 'adminUser'])->name('adminUser');
    Route::get('/admin-userList/{id}', [AdminController::class, 'adminUserList'])->name('adminUserList');
    Route::post('/admin-home-addUser', [AdminController::class, 'addPost'])->name('addPost');
    Route::post('/admin-home-editUser', [AdminController::class, 'editUserPost'])->name('editUserPost');
    Route::post('/admin-home-removeUser', [AdminController::class, 'removeUserPost'])->name('removeUserPost');
    Route::post('/admin-home-addSubscription', [AdminController::class, 'addSubscriptionPost'])->name('addSubscriptionPost');
    Route::post('/admin-home-editSubscription', [AdminController::class, 'editSubscriptionPost'])->name('editSubscriptionPost');
    Route::post('/admin-home-editSubscriptionDetail', [AdminController::class, 'editSubscriptionDetail'])->name('editSubscriptionDetail');
    Route::post('/admin-home-removeSubscription', [AdminController::class, 'removeSubscriptionPost'])->name('removeSubscriptionPost');
    Route::post('/admin-home-cancelSubscription', [AdminController::class, 'cancelSubscription'])->name('cancelSubscription');



});
