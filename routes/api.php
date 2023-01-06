<?php

use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("v1")->middleware('auth:sanctum')->group(function ()
{
    Route::post('invoices/bulk', [InvoiceController::class, "bulkStore"]);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('invoices', InvoiceController::class);
});

Route::get('/setup', function ()
{
    Auth::logout();
    $tokens = [];
    $credentials = [
      'email' => 'admin@neocode.com',
      'password' => '1595'
    ];
    if (!Auth::attempt($credentials))
    {
        $user = new User();
        $user->name = 'admin';
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->save();

        if (Auth::attempt($credentials))
        {
            $user = Auth::user();
            $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
            $updateToken = $user->createToken('update-token', ['create', 'update']);
            $basicToken = $user->createToken('basic-token');

            $tokens = [$adminToken, $updateToken, $basicToken];
        }

    }
    else
    {
        $user = Auth::user();
        foreach ($user->tokens as $token)
        {
            $tokens[] = $token->token;
        }
    }
    return $tokens;
});
