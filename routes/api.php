<?php

use App\Http\Controllers\PinCheckController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SingerRepertoireController;
use App\Http\Controllers\Music\MusicDestroyController;
use App\Http\Controllers\Music\MusicIndexController;
use App\Http\Controllers\Music\MusicShowController;
use App\Http\Controllers\Music\MusicStoreController;
use App\Http\Controllers\Music\MusicUpdateController;
use App\Http\Controllers\MusicRepertoireController;
use App\Http\Controllers\Repertoire\RepertoireController;
use App\Http\Controllers\Repertoire\RepertoireDestroyController;
use App\Http\Controllers\Repertoire\RepertoireIndexController;
use App\Http\Controllers\Repertoire\RepertoireShowController;
use App\Http\Controllers\Repertoire\RepertoireStoreController;
use App\Http\Controllers\Repertoire\RepertoireUpdateController;
use App\Http\Controllers\RepertoireMusicController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\Type\TypeDestroyController;
use App\Http\Controllers\Type\TypeIndexController;
use App\Http\Controllers\Type\TypeShowController;
use App\Http\Controllers\Type\TypeStoreController;
use App\Http\Controllers\Type\TypeUpdateController;
use App\Http\Controllers\Singer\SingerDestroyController;
use App\Http\Controllers\Singer\SingerIndexController;
use App\Http\Controllers\Singer\SingerShowController;
use App\Http\Controllers\Singer\SingerStoreController;
use App\Http\Controllers\Singer\SingerUpdateController;
use App\Http\Controllers\SingerMusicController;
use App\Http\Controllers\TonesController;
use App\Http\Controllers\User\UserLoginController;
use App\Http\Controllers\User\UserRegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::post('/login', UserLoginController::class);
    Route::post('/register', UserRegisterController::class);

    Route::prefix('forgot-password')->group(function () {
        Route::post('',  ForgotPasswordController::class);
        Route::post('/pin', PinCheckController::class);
        Route::post('/new-password', ResetPasswordController::class);
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('singer')->group(function () {
        Route::post('', SingerStoreController::class);
        Route::get('', SingerIndexController::class);
        Route::prefix('{singerId}')->group(function () {
            Route::delete('', SingerDestroyController::class);
            Route::get('', SingerShowController::class);
            Route::post('', SingerUpdateController::class);
            Route::post('/music/{musicId}', [SingerMusicController::class, 'bind']);
        });
        Route::delete('/music/{singerMusicId}', [SingerMusicController::class, 'unbind']);
    });

    Route::prefix('type')->group(function () {
        Route::post('', TypeStoreController::class);
        Route::get('', TypeIndexController::class);
        Route::delete('/{typeId}', TypeDestroyController::class);
        Route::post('/{typeId}', TypeUpdateController::class);
    });

    Route::prefix('music')->group(function () {
        Route::post('', MusicStoreController::class);
        Route::get('', MusicIndexController::class);
        Route::delete('/{musicId}', MusicDestroyController::class);
        Route::get('/{musicId}', MusicShowController::class);
        Route::post('/{musicId}', MusicUpdateController::class);

        Route::get('/repertoire/{repertoireId}', [MusicRepertoireController::class, 'getMusicsByRepertoire']);
    });

    Route::prefix('repertoire')->group(function () {
        Route::post('', RepertoireStoreController::class);
        Route::get('', RepertoireIndexController::class);
        Route::delete('/{repertoireId}', RepertoireDestroyController::class);
        Route::get('/{repertoireId}', RepertoireShowController::class);
        Route::post('/{repertoireId}', RepertoireUpdateController::class);

        Route::get('/music/{musicId}', [RepertoireMusicController::class, 'getRepertoiresByMusic']);

        Route::prefix('{repertoireId}')->group(function () {
            Route::put('/musics', [MusicRepertoireController::class, 'unbind']);
            Route::put('/singers', [SingerRepertoireController::class, 'unbind']);
        });
    });

    Route::prefix('tone')->group(function () {
        Route::get('', [TonesController::class, 'index']);
    });

    Route::post('/user/repertoire/update/{repertoireId}', [RepertoireController::class, 'update']);
});
