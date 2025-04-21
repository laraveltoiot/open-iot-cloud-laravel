<?php declare(strict_types=1);

use App\Http\Controllers\NodeController;
use App\Http\Controllers\PairingController;
use App\Models\Node;
use App\Models\UserNodeMapping;
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



// Route::get('/debug-token', function (Request $request) {
//    return [
//        'user' => $request->user(),
//        'accessToken' => $request->user()?->currentAccessToken(),
//        'abilities' => $request->user()?->currentAccessToken()?->abilities,
//    ];
// })->middleware('auth:sanctum');
