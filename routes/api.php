<?php

use App\Core\Enums\Role\RoleEnum;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Authority\AuthorityController;
use App\Http\Controllers\Checklist\ChecklistAuthorityController;
use App\Http\Controllers\Checklist\ChecklistController;
use App\Http\Controllers\Enum\IndicatorController;
use App\Http\Controllers\Enum\IndicatorTypeController;
use App\Http\Controllers\Enum\SoatoRegionController;
use App\Http\Controllers\File\FileManagerController;
use App\Http\Controllers\Integration\MibIntegrationController;
use App\Http\Controllers\Question\QuestionAuthorityController;
use App\Http\Controllers\Question\QuestionController;
use App\Http\Controllers\Request\RequestController;
use App\Http\Controllers\User\UserController;
use App\Http\Middleware\CheckRole;
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

Route::controller(AuthController::class)
    ->group(function () {
        Route::get('auth/login-by-one-id', 'loginByOneId')
            ->name('auth.login-by-one-id');

        Route::post('auth/login-by-user', 'loginByUser')
            ->name('auth.login-by-user');

        Route::get('auth/logout', 'logout')
            ->name('auth.logout');

        Route::get('auth/refresh-token', 'refreshToken')
            ->name('auth.refresh-token');

    });

Route::controller(AuthController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        Route::get('user/detail', 'detail')
            ->name('auth.detail');

    });

Route::controller(UserController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        Route::get('user/list', 'list')
            ->name('user.list');

        Route::post('user/filter', 'filter')
            ->name('user.filter');

        Route::get('user/get/{id}', 'get')
            ->name('user.get');

        Route::post('user/create', 'create')
            ->middleware(CheckRole::class . ':' . RoleEnum::_SUPER_ADMIN->value)
            ->name('user.create');

        Route::post('user/update/{id}', 'update')
            ->middleware(CheckRole::class . ':' . RoleEnum::_SUPER_ADMIN->value)
            ->name('user.update');

        Route::get('user/delete/{id}', 'delete')
            ->middleware(CheckRole::class . ':' . RoleEnum::_SUPER_ADMIN->value)
            ->name('user.delete');

        Route::get('user/ban/{id}', 'ban')
            ->middleware(CheckRole::class . ':' . RoleEnum::_SUPER_ADMIN->value)
            ->name('user.ban');

        Route::get('user/unban/{id}', 'unban')
            ->middleware(CheckRole::class . ':' . RoleEnum::_SUPER_ADMIN->value)
            ->name('user.unban');
    });

Route::controller(IndicatorTypeController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        $roles = implode(',', [
            RoleEnum::_SUPER_ADMIN->value,
            RoleEnum::_RESPONSIBLE->value,
        ]);
        Route::get('indicator-type/list', 'list')
            ->name('indicator-type.list');

        Route::post('indicator-type/filter', 'filter')
            ->name('indicator-type.filter');

        Route::get('indicator-type/get/{id}', 'get')
            ->name('indicator-type.get');

        Route::post('indicator-type/create', 'create')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('indicator-type.create');

        Route::post('indicator-type/update/{id}', 'update')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('indicator-type.update');

        Route::get('indicator-type/delete/{id}', 'delete')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('indicator-type.delete');
    });

Route::controller(IndicatorController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        $roles = implode(',', [
            RoleEnum::_SUPER_ADMIN->value,
            RoleEnum::_RESPONSIBLE->value,
        ]);
        Route::get('indicator/list', 'list')
            ->name('indicator.list');

        Route::post('indicator/filter', 'filter')
            ->name('indicator.filter');

        Route::get('indicator/get/{id}', 'get')
            ->name('indicator.get');

        Route::post('indicator/create', 'create')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('indicator.create');

        Route::post('indicator/update/{id}', 'update')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('indicator.update');

        Route::get('indicator/delete/{id}', 'delete')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('indicator.delete');

        Route::post('indicator/scores', 'scores')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('indicator.scores');
    });

Route::controller(SoatoRegionController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        Route::get('soato-region/list', 'list')
            ->name('soatoRegion.list');
    });

Route::controller(RequestController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        $roles = implode(',', [
            RoleEnum::_SUPER_ADMIN->value,
            RoleEnum::_RESPONSIBLE->value,
        ]);
        Route::get('request/list', 'list')
            ->name('request.list');

        Route::post('request/filter', 'filter')
            ->name('request.filter');

        Route::post('request/create', 'create')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('request.create');

        Route::post('request/update/{id}', 'update')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('request.update');

        Route::get('request/get/{id}', 'get')
            ->name('request.get');

        Route::get('request/delete/{id}', 'delete')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('request.delete');

        Route::post('score-indicator-request/set-point/{id}', 'setPoint')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('request.setPoint');

        Route::post('score-indicator-request/set-file/{id}', 'setFile')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('request.setFile');

        Route::post('request/confirm/{id}', 'confirm')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('request.confirm');

        Route::get('request/scored/{id}', 'scored')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('request.scored');

        Route::get('request/stat', 'stat')
            ->name('request.stat');

        Route::get('request/generate-pdf/{id}', 'generatePdf')
            ->name('request.generate-pdf');

        Route::get('request/log/{id}', 'log')
            ->name('request.log');
    });

Route::controller(QuestionController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        $roles = implode(',', [
            RoleEnum::_SUPER_ADMIN->value,
            RoleEnum::_RESPONSIBLE->value,
        ]);
        Route::post('question/create', 'create')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('question.create');

        Route::post('question/update/{id}', 'update')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('question.update');

        Route::get('question/delete/{id}', 'delete')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('question.delete');

        Route::get('question/get/{id}', 'get')
            ->name('question.get');

        Route::post('question/filter', 'filter')
            ->name('question.filter');

        Route::get('question/list', 'list')
            ->name('question.list');
    });

Route::controller(ChecklistController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        $roles = implode(',', [
            RoleEnum::_SUPER_ADMIN->value,
            RoleEnum::_RESPONSIBLE->value,
        ]);
        Route::post('checklist/create', 'create')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('checklist.create');

        Route::post('checklist/update/{id}', 'update')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('checklist.update');

        Route::get('checklist/delete/{id}', 'delete')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('checklist.delete');

        Route::get('checklist/get/{id}', 'get')
            ->name('checklist.get');

        Route::post('checklist/filter', 'filter')
            ->name('checklist.filter');

        Route::get('checklist/list/{id}', 'list')
            ->name('checklist.list');
    });


Route::controller(ChecklistAuthorityController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        $rolesWithAuthority = implode(',', [
            RoleEnum::_AUTHORITY->value,
        ]);
        Route::post('checklist-authority/check/{id}', 'check')
            ->middleware(CheckRole::class . ':' . $rolesWithAuthority)
            ->name('checklist-authority.check');

        Route::get('checklist-authority/confirm/{id}', 'confirm')
            ->middleware(CheckRole::class . ':' . $rolesWithAuthority)
            ->name('checklist-authority.confirm');
    });

Route::controller(MibIntegrationController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        $roles = implode(',', [
            RoleEnum::_SUPER_ADMIN->value,
            RoleEnum::_RESPONSIBLE->value,
            RoleEnum::_TERRITORIAL_RESPONSIBLE->value,
        ]);

        Route::post('mib-integration/get', 'get')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('mib-integration.get');

    });

Route::controller(QuestionAuthorityController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        $rolesWithAuthority = implode(',', [
            RoleEnum::_AUTHORITY->value,
        ]);
        Route::get('question-authority/read', 'read')
            ->middleware(CheckRole::class . ':' . $rolesWithAuthority)
            ->name('question-authority.read');
    });

Route::controller(AuthorityController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        $roles = implode(',', [
            RoleEnum::_SUPER_ADMIN->value,
            RoleEnum::_RESPONSIBLE->value,
        ]);
        Route::post('authority/filter', 'filter')
            ->name('authority.filter');

        Route::get('authority/count-checked', 'countChecked')
            ->name('authority.countChecked');

        Route::get('authority/count-question', 'countQuestion')
            ->name('authority.countQuestion');

        Route::post('authority/excel-upload', 'excelUpload')
            ->name('authority.excelUpload');

        Route::post('authority/get/{id}', 'get')
            ->middleware(CheckRole::class . ':' . $roles)
            ->name('authority.get');
    });

Route::controller(FileManagerController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        Route::post('file/upload', 'upload')
            ->name('file.upload');

        Route::get('file/download/{id}', 'download')
            ->name('file.download');
    });




