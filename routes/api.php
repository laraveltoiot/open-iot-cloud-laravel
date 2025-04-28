<?php declare(strict_types=1);

use App\Http\Controllers\HeartbeatController;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\PairingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        if (! $request->user()?->tokenCan('read')) {
            return response()->json(['message' => 'Unauthorized - Missing permission: read'], 403);
        }

        return $request->user();
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/nodes', [NodeController::class, 'index']);
    Route::post('/create-node', [NodeController::class, 'store']);
    Route::get('/nodes/{id}', [NodeController::class, 'show']);
    Route::get('/nodes/{id}', [NodeController::class, 'edit']);
    Route::patch('/nodes/{id}', [NodeController::class, 'update']);
    Route::delete('/nodes/{id}', [NodeController::class, 'destroy']);
});

Route::post('/iot/heartbeat', [HeartbeatController::class, 'store']);
Route::post('/iot/pairing', [PairingController::class, 'store']);

Route::post('/webhook/test', function (Request $request) {
    Log::info('Received Webhook Test:', [
        'headers' => $request->headers->all(),
        'body' => $request->all(),
    ]);

    return response()->json(['status' => 'ok']);
});


// Route::get('/debug-token', function (Request $request) {
//    return [
//        'user' => $request->user(),
//        'accessToken' => $request->user()?->currentAccessToken(),
//        'abilities' => $request->user()?->currentAccessToken()?->abilities,
//    ];
// })->middleware('auth:sanctum');
