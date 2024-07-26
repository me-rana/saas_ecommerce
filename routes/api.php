<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;


Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function () {
    //Register Input(name,phone and password)
    Route::post('/register', [CustomerController::class, 'register'])->name('register');
    //Login Input(phone and password)
    Route::post('/login', [CustomerController::class, 'login'])->name('login');
    //Forget Input(password)
    Route::post('/forget', [CustomerController::class, 'forget'])->name('forget');
    //Reset Input(phone,otp and password)
    Route::post('/reset', [CustomerController::class, 'reset'])->name('reset');
    //Socialite Authentication
    Route::get('auth/google/redirect/', [CustomerController::class, 'redirect']);
    Route::get('auth/google/callback/', [CustomerController::class, 'callback']);

    //Category Management-------------------------------------------------------------------------
    Route::get('/categories',[CustomerController::class, 'categories'])->name('Categories');
    //Input id--(category)
    Route::post('/sub-categories',[CustomerController::class, 'subCategories'])->name('Sub Categories');
    //Input id--(category and sub_category)
    Route::post('/child-categories',[CustomerController::class, 'childCategories'])->name('Child Categories');

    //Product Management-------------------------------------------------------------------
    // Filter Input filter_by(a-z,z-a,latest,older,high-to-low,low-to-high) and (category_id,sub_category_id,child_category_id)
    Route::post('/category-products',[CustomerController::class,'categoryProduct'])->name('Category Product');
    // Filter (a-z,z-a,latest,older,high-to-low,low-to-high)
    Route::post('/products', [CustomerController::class,'products'])->name('All Products');
    
});


Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'v1'
],function(){
    Route::post('/logout', [CustomerController::class, 'logout'])->name('logout');
    Route::post('/refresh', [CustomerController::class, 'refresh'])->name('refresh');
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::post('/update-profile', [CustomerController::class,'updateProfile'])->name('update_profile');
    Route::post('/change-password', [CustomerController::class, 'changePassword'])->name('change_password');

    //Cart Management
    Route::post('/add-to-cart',[CustomerController::class,'addToCart'])->name('Add to Cart');
    Route::get('/carts',[CustomerController::class, 'carts'])->name('Carts');
    Route::post('/cart-increment',[CustomerController::class, 'cartIncrement'])->name('Cart Increment');
    Route::post('/cart-decrement',[CustomerController::class, 'cartDecrement'])->name('Cart Decrement');
    Route::post('/cart-remove',[CustomerController::class, 'cartRemove'])->name('Cart Remove');
    Route::get('/cart-clear',[CustomerController::class, 'cartClear'])->name('Cart Clear');
    
    //Cart Orders
    Route::post('/place-order',[CustomerController::class,'placeOrder'])->name('Place Order');
    Route::get('/order-list',[CustomerController::class, 'orderList'])->name('Order List');
    Route::post('/order-details',[CustomerController::class, 'orderDetails'])->name('Order Details');
});


