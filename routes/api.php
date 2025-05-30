<?php declare(strict_types=1);

use App\Http\Controllers\DeviceBootstrapController;
use App\Http\Controllers\HeartbeatController;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\PairingController;
use App\Http\Controllers\WebhookController;
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
    Route::apiResource('webhooks', WebhookController::class);
});

Route::post('/iot/heartbeat', [HeartbeatController::class, 'store']);

Route::post('/webhook/test', function (Request $request) {
    Log::info('Received Webhook Test:', [
        'headers' => $request->headers->all(),
        'body' => $request->all(),
    ]);

    return response()->json(['status' => 'ok']);
});

// Zero-Touch Provisioning endpoint that combines pairing and credential retrieval
Route::post('/devices/bootstrap', [DeviceBootstrapController::class, 'store']);
