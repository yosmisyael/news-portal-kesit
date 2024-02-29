<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HeadlineManagementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostManagementController;
use App\Http\Controllers\SubmissionManagementController;
use App\Http\Controllers\SuspensionManagementController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserPostController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserSubmissionController;
use Illuminate\Support\Facades\Route;

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

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('public.homepage');
    Route::get('/about', 'getAbout')->name('public.about');
    Route::get('/contact', 'getContact')->name('public.contact');
});

Route::prefix('/auth')->controller(UserAuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'login')->name('user.auth.login');
        Route::post('/login', 'postLogin')->name('user.auth.postLogin');
    });
    Route::delete('/logout', 'logout')->middleware('auth')->name('user.auth.logout');
});

Route::prefix('/dashboard/{username}')->where(['username' => '^@[a-z0-9]+\d*$'])->middleware('auth')->group(function () {
    Route::get('/', UserDashboardController::class)->name('user.dashboard');
    Route::prefix('/profile')->controller(UserProfileController::class)->group(function () {
        Route::get('/', 'show')->name('user.profile.show');
        Route::get('/edit', 'edit')->name('user.profile.edit');
        Route::put('/', 'update')->name('user.profile.update');
        Route::post('/picture', 'updateProfile')->name('user.profile.updateProfile');
        Route::get('/reset', 'reset')->name('user.profile.reset');
        Route::patch('/reset', 'patchReset')->name('user.profile.patchReset');
    });
    Route::prefix('/post')->group(function () {
    Route::controller(UserPostController::class)->group(function () {
        Route::get('/', 'index')->name('user.post.index');
        Route::get('/create', 'create')->name('user.post.create');
        Route::post('/', 'store')->name('user.post.store');
        Route::get('/{id}', 'show')->name('user.post.show');
        Route::post('/image', 'storePicture')->name('user.post.storePict');
        Route::get('/{id}/edit', 'edit')->name('user.post.edit');
        Route::put('/{id}', 'update')->name('user.post.update');
        Route::delete('/{id}', 'destroy')->name('user.post.destroy');
    });
    Route::prefix('/{postId}/submissions')->controller(UserSubmissionController::class)->group(function () {
        Route::get('/', 'index')->name('user.submission.index');
        Route::post('/', 'store')->name('user.submission.store');
        Route::get('/{submissionId}', 'show')->name('user.submission.show');
    });
});
});

Route::prefix('/control-panel')->group(function () {
    Route::prefix('/auth')->controller(AdminAuthController::class)->group(function () {
       Route::middleware('guest.admin')->group(function () {
           Route::get('/login', 'login')->name('admin.auth.login');
           Route::post('/login', 'postLogin')->name('admin.auth.postLogin');
       });
       Route::middleware('auth.admin')->group(function () {
           Route::delete('/logout', 'logout')->name('admin.auth.logout');
       });
   });
    Route::middleware('auth.admin')->group(function () {
        Route::get('/index', AdminDashboardController::class)->name('admin.dashboard');
        Route::prefix('/headline')->controller(HeadlineManagementController::class)->group(function () {
            Route::get('/', 'index')->name('admin.headline.index');
            Route::post('/', 'store')->name('admin.headline.store');
            Route::get('/{id}/edit', 'edit')->name('admin.headline.edit');
            Route::put('/{id}', 'update')->name('admin.headline.update');
            Route::delete('/{id}', 'destroy')->name('admin.headline.destroy');
        });
        Route::prefix('/user')->controller(UserManagementController::class)->group(function () {
            Route::get('/', 'index')->name('admin.user.index');
            Route::get('/create', 'create')->name('admin.user.create');
            Route::post('/', 'store')->name('admin.user.store');
            Route::get('/{id}/edit', 'edit')->name('admin.user.edit');
            Route::put('/{id}', 'update')->name('admin.user.update');
            Route::delete('/{id}', 'destroy')->name('admin.user.destroy');
        });
        Route::prefix('/post')->group(function () {
            Route::controller(PostManagementController::class)->group(function () {
                Route::get('/', 'index')->name('admin.post.index');
                Route::get('/{id}', 'show')->name('admin.post.show');
            });
            Route::controller(SuspensionManagementController::class)->group(function () {
               Route::get('/{id}/suspension', 'create')->name('admin.suspension.create');
               Route::post('/{id}/suspension', 'store')->name('admin.suspension.store');
            });
        });
        Route::prefix('/submission')->controller(SubmissionManagementController::class)->group(function () {
            Route::get('/', 'index')->name('admin.submission.index');
            Route::get('/{submissionId}', 'show')->name('admin.submission.show');
            Route::get('/{submissionId}/review/create', 'create')->name('admin.review.create');
            Route::post('/{submissionId}/review', 'store')->name('admin.review.store');
        });
        Route::prefix('/category')->controller(CategoryController::class)->group(function () {
            Route::get('/', 'index')->name('admin.category.index');
            Route::get('/create', 'create')->name('admin.category.create');
            Route::post('/', 'store')->name('admin.category.store');
            Route::get('/{id}/edit', 'edit')->name('admin.category.edit');
            Route::put('/{id}', 'update')->name('admin.category.update');
            Route::delete('/{id}', 'destroy')->name('admin.category.destroy');
        });
    });
});
