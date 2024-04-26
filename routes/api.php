<?php

use App\Enums\PermissionEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TableNumberController;
use App\Http\Controllers\ItemDataController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialDataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransferItemController;
use App\Http\Controllers\TransferMaterialController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TablePackageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/forget-password', [PasswordResetController::class, 'forgetPassword'])->middleware('guest');
Route::get('/reset-password', [PasswordResetController::class, 'resetPasswordPage'])->middleware('guest');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->middleware('guest');

Route::group(['prefix' => 'auth'], function () {
    Route::group(['prefix' => 'role'], function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::post('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });

    Route::group(['prefix' => 'permission'], function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/{id}', [PermissionController::class, 'show']);

    });

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/{id}/change-password', [AuthController::class, 'changePassword']);
});

Route::middleware('jwt')->group(function () {
    Route::group(['prefix' => 'user'], function () {
        Route::get('/exportexcel', [UserController::class, 'exportexcel']);
        Route::get('/exportexcelparams', [UserController::class, 'exportparams']);
        Route::get('/exportpdf', [UserController::class, 'exportpdf']);
        Route::get('/exportpdfparams', [UserController::class, 'exportpdfparams']);
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::post('/assign-role', [UserController::class, 'assignRole']);
        Route::post('/remove-role', [UserController::class, 'removeRole']);
        Route::post('/import', [UserController::class, 'import']);
    });

    Route::group(['prefix' => 'shop'], function () {
        Route::get('/exportexcel', [ShopController::class, 'exportexcel']);
        Route::get('/exportexcelparams', [ShopController::class, 'exportparams']);
        Route::get('/exportpdf', [ShopController::class, 'exportpdf']);
        Route::get('/exportpdfparams', [ShopController::class, 'exportpdfparams']);
        Route::get('/', [ShopController::class, 'index']);
        Route::post('/', [ShopController::class, 'store']);
        Route::get('/{id}', [ShopController::class, 'show']);
        Route::post('/{id}', [ShopController::class, 'update']);
        Route::delete('/{id}', [ShopController::class, 'destroy']);
        Route::post('/import', [ShopController::class, 'import']);
    });

    Route::group(['prefix' => 'cashier'], function () {
        Route::get('/', [CashierController::class, 'index']);
        Route::post('/', [CashierController::class, 'store']);
        Route::get('/{id}', [CashierController::class, 'show']);
        Route::post('/{id}', [CashierController::class, 'update']);
        Route::delete('/{id}', [CashierController::class, 'destroy']);
    });

    Route::group(['prefix' => 'customer'], function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('/{id}', [CustomerController::class, 'show']);
        Route::post('/{id}', [CustomerController::class, 'update']);
        Route::delete('/{id}', [CustomerController::class, 'destroy']);
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/exportexcel', [CategoryController::class, 'exportexcel']);         
        Route::get('/exportexcelparams', [CategoryController::class, 'exportparams']);       
        Route::get('/exportpdf', [CategoryController::class, 'exportpdf']);      
        Route::get('/exportpdfparams', [CategoryController::class, 'exportpdfparams']);      
        Route::get('/', [CategoryController::class, 'index']);       
        Route::post('/', [CategoryController::class, 'store']);         
        Route::get('/{id}', [CategoryController::class, 'show']);          
        Route::post('/{id}', [CategoryController::class, 'update']);        
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    Route::group(['prefix' => 'item'], function () {
        Route::get('/exportexcel', [ItemController::class, 'exportexcel']);
        Route::get('/exportexcelparams', [ItemController::class, 'exportparams']); 
        Route::get('/exportpdf', [ItemController::class, 'exportpdf']);
        Route::get('/exportpdfparams', [ItemController::class, 'exportpdfparams']);
        Route::get('/', [ItemController::class, 'index']);        
        Route::post('/', [ItemController::class, 'store']);        
        Route::get('/{id}', [ItemController::class, 'show']);         
        Route::post('/{id}', [ItemController::class, 'update']);          
        Route::patch('/{id}', [ItemController::class, 'update']);         
        Route::delete('/{id}', [ItemController::class, 'destroy']);  
        Route::post('/import', [ItemController::class, 'import']);
    });

    Route::group(['prefix' => 'material'], function () {
        Route::get('/exportexcel', [MaterialController::class, 'exportexcel']);
        Route::get('/exportexcelparams', [MaterialController::class, 'exportparams']); 
        Route::get('/exportpdf', [MaterialController::class, 'exportpdf']);
        Route::get('/exportpdfparams', [MaterialController::class, 'exportpdfparams']);
        Route::get('/', [MaterialController::class, 'index']); 
        Route::post('/', [MaterialController::class, 'store']);         
        Route::get('/{id}', [MaterialController::class, 'show']);           
        Route::post('/{id}', [MaterialController::class, 'update']);           
        Route::patch('/{id}', [MaterialController::class, 'update']);          
        Route::delete('/{id}', [MaterialController::class, 'destroy']);    
        Route::post('/import', [MaterialController::class, 'import']);
    });

    Route::group(['prefix' => 'itemData'], function () {
        Route::get('/exportexcel', [ItemDataController::class, 'exportexcel']);        
        Route::get('/exportexcelparams', [ItemDataController::class, 'exportparams']);         
        Route::get('/exportpdf', [ItemDataController::class, 'exportpdf']);        
        Route::get('/exportpdfparams', [ItemDataController::class, 'exportpdfparams']);        
        Route::get('/', [ItemDataController::class, 'index']);         
        Route::post('/', [ItemDataController::class, 'store']);       
        Route::get('/{id}', [ItemDataController::class, 'show']);         
        Route::post('/{id}', [ItemDataController::class, 'update']);           
        Route::patch('/{id}', [ItemDataController::class, 'update']);          
        Route::delete('/{id}', [ItemDataController::class, 'destroy']);      
        Route::post('/import', [ItemDataController::class, 'import']);
    });

    Route::group(['prefix' => 'materialData'], function () {
        Route::get('/exportexcel', [MaterialDataController::class, 'exportexcel']);       
        Route::get('/exportexcelparams', [MaterialDataController::class, 'exportparams']);        
        Route::get('/exportpdf', [MaterialDataController::class, 'exportpdf']);       
        Route::get('/exportpdfparams', [MaterialDataController::class, 'exportpdfparams']);       
        Route::get('/', [MaterialDataController::class, 'index']);        
        Route::post('/', [MaterialDataController::class, 'store']);         
        Route::get('/{id}', [MaterialDataController::class, 'show']);         
        Route::post('/{id}', [MaterialDataController::class, 'update']);         
        Route::patch('/{id}', [MaterialDataController::class, 'update']);        
        Route::delete('/{id}', [MaterialDataController::class, 'destroy']);       
        Route::post('/import', [MaterialDataController::class, 'import']);
    });

    Route::group(['prefix' => 'transferItem'], function () {
        Route::get('/exportexcel', [TransferItemController::class, 'exportexcel']);       
        Route::get('/exportexcelparams', [TransferItemController::class, 'exportparams']);        
        Route::get('/exportpdf', [TransferItemController::class, 'exportpdf']);       
        Route::get('/exportpdfparams', [TransferItemController::class, 'exportpdfparams']);       
        Route::get('/', [TransferItemController::class, 'index']);        
        Route::post('/', [TransferItemController::class, 'store']);         
        Route::get('/{id}', [TransferItemController::class, 'show']);           
        Route::post('/{id}', [TransferItemController::class, 'update']);            
        Route::delete('/{id}', [TransferItemController::class, 'destroy']);       
    });

    Route::group(['prefix' => 'transferMaterial'], function () {
        Route::get('/exportexcel', [TransferMaterialController::class, 'exportexcel']);         
        Route::get('/exportexcelparams', [TransferMaterialController::class, 'exportparams']);          
        Route::get('/exportpdf', [TransferMaterialController::class, 'exportpdf']);         
        Route::get('/exportpdfparams', [TransferMaterialController::class, 'exportpdfparams']);         
        Route::get('/', [TransferMaterialController::class, 'index']);          
        Route::post('/', [TransferMaterialController::class, 'store']);        
        Route::get('/{id}', [TransferMaterialController::class, 'show']);           
        Route::post('/{id}', [TransferMaterialController::class, 'update']);            
        Route::delete('/{id}', [TransferMaterialController::class, 'destroy']);        
    });

    Route::group(['prefix' => 'table'], function () {
        Route::get('/', [TableNumberController::class, 'index']);          
        Route::post('/', [TableNumberController::class, 'store']);          
        Route::get('/{id}', [TableNumberController::class, 'show']);          
        Route::post('/{id}', [TableNumberController::class, 'update']);         
        Route::patch('/{id}', [TableNumberController::class, 'update']);           
        Route::delete('/{id}', [TableNumberController::class, 'destroy']);         
    });

    Route::group(['prefix' => 'order'], function () {
        Route::get('/', [OrderController::class, 'index']);         
        Route::post('/', [OrderController::class, 'store']);           
        Route::get('/{id}', [OrderController::class, 'show']);        
        Route::post('/{id}', [OrderController::class, 'update']);         
        Route::patch('/{id}', [OrderController::class, 'update']);        
        Route::delete('/{id}', [OrderController::class, 'destroy']);           
    });

    Route::group(['prefix' => 'invoice-item'], function () {
        Route::get('/', [InvoiceItemController::class, 'index']);         
        Route::post('/', [InvoiceItemController::class, 'store']);        
        Route::get('/{id}', [InvoiceItemController::class, 'show']);           
        Route::post('/{id}', [InvoiceItemController::class, 'update']);       
        Route::patch('/{id}', [InvoiceItemController::class, 'update']);          
        Route::delete('/{id}', [InvoiceItemController::class, 'destroy']);         
    });

    Route::group(['prefix' => 'table-package'], function () {
        Route::get('/', [TablePackageController::class, 'index']);         
        Route::post('/', [TablePackageController::class, 'store']);        
        Route::get('/{id}', [TablePackageController::class, 'show']);           
        Route::post('/{id}', [TablePackageController::class, 'update']);       
        Route::patch('/{id}', [TablePackageController::class, 'update']);          
        Route::delete('/{id}', [TablePackageController::class, 'destroy']);         
    });

    Route::group(['prefix' => 'bill'], function () {
        Route::get('/', [BillController::class, 'index']);         
        Route::post('/', [BillController::class, 'store']);        
        Route::get('/{id}', [BillController::class, 'show']);           
        Route::post('/{id}', [BillController::class, 'update']);       
        Route::patch('/{id}', [BillController::class, 'update']);          
        Route::delete('/{id}', [BillController::class, 'destroy']);         
    });

    Route::group(['prefix' => 'payment'], function () {
        Route::get('/', [PaymentController::class, 'index']);         
        Route::post('/', [PaymentController::class, 'store']);        
        Route::get('/{id}', [PaymentController::class, 'show']);           
        Route::post('/{id}', [PaymentController::class, 'update']);       
        Route::patch('/{id}', [PaymentController::class, 'update']);          
        Route::delete('/{id}', [PaymentController::class, 'destroy']);         
    });

    Route::group(['prefix' => 'invoice'], function () {
        Route::get('/', [InvoiceController::class, 'index']);           
        Route::post('/', [InvoiceController::class, 'store']);         
        Route::get('/{id}', [InvoiceController::class, 'show']);           
        Route::post('/{id}', [InvoiceController::class, 'update']);        
        Route::delete('/{id}', [InvoiceController::class, 'destroy']);          
    });

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'getDashboardData']);
    });

    Route::group(['prefix' => 'printer'], function () {
        Route::get('/', [PrinterController::class, 'index']);
        Route::post('/{id}', [PrinterController::class, 'update']);
        Route::get('/print-invoice/{id}', [PrinterController::class, 'printInvoice']);
        Route::get('/print-kitchen/{id}', [PrinterController::class, 'printKitchen']);
        Route::get('/print-bar/{id}', [PrinterController::class, 'printBar']);
    });

    Route::get('/invoice-export', [InvoiceController::class, 'export']);
});

Route::get('/image/{path}', [ItemController::class, 'getImage'])->where('path', '.*');
