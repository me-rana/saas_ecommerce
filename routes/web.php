<?php

use App\Events\DashbEvent;
use App\Events\DashManager;
use App\Events\NotifyEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\SslCommerzPaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-queue', function () {
    \Illuminate\Support\Facades\Artisan::call('queue:work');
    return 'Queue worker started';
});


Route::get('/testing',function(){
    // event(new DashbEvent());
    event(new NotifyEvent('New Message Received!'));
    return response()->json(['message' => 'Testing executed successfully'],200);
});

Route::get('/logout', function(){
    Auth::logout();
    return redirect('/login');
});




// SSLCOMMERZ Start
Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'dashboard'])->name('dashboard');

    //Category Management
    Route::get('/category/manage', [CategoryController::class,'index'])->middleware(['role_or_permission:manage categories'])->name('Category Management');
    Route::get('/category/create', [CategoryController::class,'create'])->middleware(['role_or_permission:create categories'])->name('Category Create');
    Route::post('/category/store', [CategoryController::class,'store'])->middleware(['role_or_permission:create categories'])->name('Category Store');
    Route::get('/category/edit/{id}', [CategoryController::class,'edit'])->middleware(['role_or_permission:edit categories'])->name('Category Edit');
    Route::post('/category/update', [CategoryController::class,'update'])->middleware(['role_or_permission:edit categories'])->name('Category Update');
    Route::get('/category/delete/{id}', [CategoryController::class,'destroy'])->middleware(['role_or_permission:publish categories'])->name('Category Delete');
    Route::post('/category/status', [CategoryController::class,'status'])->middleware(['role_or_permission:publish categories'])->name('Category Status');
    //Product Management
    Route::get('/product/manage', [ProductController::class,'index'])->middleware(['role_or_permission:manage products'])->name('Product Management');
    Route::get('/product/search',[ProductController::class,'search'])->middleware(['role_or_permission:manage products'])->name('Product Search');
    Route::get('/product/create', [ProductController::class,'create'])->middleware(['role_or_permission:create products'])->name('Product Create');
    Route::post('/product/store', [ProductController::class,'store'])->middleware(['role_or_permission:create products'])->name('Product Store');
    Route::get('/product/edit/{id}', [ProductController::class,'edit'])->middleware(['role_or_permission:edit products'])->name('Product Edit');
    Route::post('/product/update', [ProductController::class,'update'])->middleware(['role_or_permission:edit products'])->name('Product Update');
    Route::get('/product/delete/{id}', [ProductController::class,'destroy'])->middleware(['role_or_permission:delete products'])->name('Product Delete');
    Route::post('/product/status', [ProductController::class,'status'])->middleware(['role_or_permission:publish product'])->name('Product Status');
    Route::post('/import',[ProductController::class, 'import'])->name('import'); 

    
    //Orders Management
    Route::get('/orders',[OrderController::class,'orders'])->middleware(['role_or_permission:manage orders'])->name('Order Management');
    Route::get('/order-status',[OrderController::class,'orderStatus'])->middleware(['role_or_permission:manage orders'])->name('Order Status');
    Route::get('/order/{id}',[OrderController::class,'orderDetails'])->middleware(['role_or_permission:manage orders'])->name('Order Details');
    //Role and Permission
    Route::get('/roles-permissions',[DashboardController::class, 'rolesPermissions'])->middleware(['role_or_permission:view permissions'])->name('Roles Permissions');
    Route::post('/assign-permission',[DashboardController::class,'assignPermission'])->middleware(['role_or_permission:view permissions'])->name('Assigned Permission');
    //User Management
    Route::get('/users/manage',[UserController::class,'manage'])->middleware(['role_or_permission:manage users'])->name('User Management');
    Route::get('/user/create',[UserController::class,'create'])->middleware(['role_or_permission:create users'])->name('User Create');
    Route::post('/user/store',[UserController::class,'store'])->middleware(['role_or_permission:create users'])->name('User Store');
    Route::get('/user/edit/{id}',[UserController::class,'edit'])->middleware(['role_or_permission:edit users'])->name('User Edit');
    Route::post('/user/update',[UserController::class,'update'])->middleware(['role_or_permission:edit users'])->name('User Update');
    Route::get('/user/delete/{id}',[UserController::class,'destroy'])->middleware(['role_or_permission:delete users'])->name('User Delete');

});



