<?php declare(strict_types=1);

use App\Http\Controllers\NodeController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\TokenManagement\ApiTokenManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/api-tokens', ApiTokenManager::class)->name('settings.api-tokens');

    Route::get('/nodes', [NodeController::class, 'index'])->name('nodes.index');
    Route::get('/create-node', [NodeController::class, 'create'])->name('nodes.create');
    Route::get('/nodes/{node}/edit', [NodeController::class, 'edit'])->name('nodes.edit');
    Route::get('/nodes/{id}', [NodeController::class, 'show'])->name('nodes.show');


});

require __DIR__.'/auth.php';
