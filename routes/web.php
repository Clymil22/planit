<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FieldTaskController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\OrganisationModuleController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', 'login');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Stores
    Route::get('/stores', [StoreController::class, 'index'])->name('stores');
    Route::get('/stores/create', [StoreController::class, 'create'])->name('stores.create');
    Route::post('/stores', [StoreController::class, 'store'])->name('stores.store');
    Route::get('/stores/{id}', [StoreController::class, 'show'])->name('stores.show');
    Route::get('/stores/{id}/edit', [StoreController::class, 'edit'])->name('stores.edit');
    Route::put('/stores/{id}', [StoreController::class, 'update'])->name('stores.update');
    Route::delete('/stores/{id}', [StoreController::class, 'destroy'])->name('stores.destroy');

    // Clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{id}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Tasks
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Field Tasks
    Route::get('/field-tasks', [FieldTaskController::class, 'index'])->name('field-tasks');
    Route::get('/field-tasks/create', [FieldTaskController::class, 'create'])->name('field-tasks.create');
    Route::post('/field-tasks', [FieldTaskController::class, 'store'])->name('field-tasks.store');
    Route::get('/field-tasks/{id}', [FieldTaskController::class, 'show'])->name('field-tasks.show');
    Route::get('/field-tasks/{id}/edit', [FieldTaskController::class, 'edit'])->name('field-tasks.edit');
    Route::put('/field-tasks/{id}', [FieldTaskController::class, 'update'])->name('field-tasks.update');
    Route::delete('/field-tasks/{id}', [FieldTaskController::class, 'destroy'])->name('field-tasks.destroy');

    // Channels
    Route::get('/channels', [ChannelController::class, 'index'])->name('channels');
    Route::get('/channels/create', [ChannelController::class, 'create'])->name('channels.create');
    Route::post('/channels', [ChannelController::class, 'store'])->name('channels.store');
    Route::get('/channels/{id}', [ChannelController::class, 'show'])->name('channels.show');
    Route::get('/channels/{id}/edit', [ChannelController::class, 'edit'])->name('channels.edit');
    Route::put('/channels/{id}', [ChannelController::class, 'update'])->name('channels.update');
    Route::delete('/channels/{id}', [ChannelController::class, 'destroy'])->name('channels.destroy');

    // Organisation Modules
    Route::get('/modules', [OrganisationModuleController::class, 'index'])->name('modules');
    Route::put('/modules', [OrganisationModuleController::class, 'update'])->name('modules.update');

    // Users/Team
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

    // Keep existing profile and settings routes
    Route::get('/profile', function () {
        return view('pages/community/profile');
    })->name('profile');

    Route::get('/settings/account', function () {
        return view('pages/settings/account');
    })->name('account');  

    Route::fallback(function() {
        return view('pages/utility/404');
    });    
});
