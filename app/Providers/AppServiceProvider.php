<?php

namespace App\Providers;

use App\Core\Filter\Authority\AuthorityFilter;
use App\Core\Filter\Checklist\ChecklistFilter;
use App\Core\Filter\Indicator\IndicatorFilter;
use App\Core\Filter\IndicatorType\IndicatorTypeFilter;
use App\Core\Filter\Question\QuestionFilter;
use App\Core\Filter\Request\RequestFilter;
use App\Core\Service\Auth\AuthService;
use App\Core\Service\Auth\Contracts\AuthContract;
use App\Core\Service\Auth\Contracts\OneIdContract;
use App\Core\Service\Auth\Contracts\UzbContract;
use App\Core\Service\Auth\OneIdService;
use App\Core\Service\Auth\UzbService;
use App\Core\Service\File\FileManagerService;
use App\Core\Service\File\interfaces\FileManager;
use App\Core\Service\Integration\OmbudsmanRequestService;
use App\Core\Service\Integration\RequestService;
use App\Core\Service\Jwt\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Eddsa;
use Lcobucci\JWT\Signer\Key\InMemory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(AuthContract::class, AuthService::class);

        $this->app->bind(OneIdContract::class, OneIdService::class);

        $this->app->bind(OneIdService::class, function () {
            return new OneIdService(
                config('idEgov.client_id'),
                config('idEgov.client_secret'),
                config('idEgov.auth_url'),
                config('idEgov.grant_one_authorization_code'),
                config('idEgov.grant_type_get_token'),
                config('idEgov.grant_type_access_token_identify'),
                config('idEgov.grant_type_logout_code')
            );
        });

        $this->app->bind(RequestService::class, function () {
            return new RequestService(
                config('mib.toke_url'),
                config('mib.service_url'),
                config('mib.grant_type'),
                config('mib.username'),
                config('mib.password'),
                config('mib.key'),
                config('mib.secret')
            );
        });

        $this->app->bind(OmbudsmanRequestService::class, function () {
            return new OmbudsmanRequestService(
                config('ombudsman.token_url'),
                config('ombudsman.service_url'),
                config('ombudsman.username'),
                config('ombudsman.password'),
            );
        });

        $this->app->when(JwtService::class)
            ->needs('$tokenExtraParams')
            ->give(config('jwt.cabinet_api'));

        $this->app->bind(Configuration::class, function () {
            return Configuration::forAsymmetricSigner(
                new Eddsa(),
                InMemory::plainText(base64_decode(config('jwt.cabinet_api.private_key'))),
                InMemory::plainText(base64_decode(config('jwt.cabinet_api.public_key')))
            );
        });

        $this->app->bind(FileManager::class, function () {
            return new FileManagerService(config('app.file.path'), config('app.file.uploads'));
        });


        $this->app->when(IndicatorTypeFilter::class)
            ->needs(Request::class)
            ->give(function () {
                return request();
            });

        $this->app->when(IndicatorFilter::class)
            ->needs(Request::class)
            ->give(function () {
                return request();
            });

        $this->app->when(RequestFilter::class)
            ->needs(Request::class)
            ->give(function () {
                return request();
            });

        $this->app->when(AuthorityFilter::class)
            ->needs(Request::class)
            ->give(function () {
                return request();
            });

        $this->app->when(QuestionFilter::class)
            ->needs(Request::class)
            ->give(function () {
                return request();
            });

        $this->app->when(ChecklistFilter::class)
            ->needs(Request::class)
            ->give(function () {
                return request();
            });

        $this->app->bind(UzbContract::class, UzbService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
