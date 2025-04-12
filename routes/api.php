<?php declare(strict_types=1);

use App\Http\Controllers\NodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        if (! $request->user()?->tokenCan('read')) {
            return response()->json(['message' => 'Unauthorized - Missing permission: read'], 403);
        }

        return $request->user();
    });

    Route::get('/nodes', function (Request $request) {
        if (! $request->user()?->tokenCan('read')) {
            return response()->json(['message' => 'Unauthorized - Missing permission: read'], 403);
        }

        return app(NodeController::class)->index();
    });
});

// Route::get('/debug-token', function (Request $request) {
//    return [
//        'user' => $request->user(),
//        'accessToken' => $request->user()?->currentAccessToken(),
//        'abilities' => $request->user()?->currentAccessToken()?->abilities,
//    ];
// })->middleware('auth:sanctum');
