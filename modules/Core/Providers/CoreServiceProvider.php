<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Core\Providers;

use Modules\Support\Helpers\Locale;
use Modules\Setting\Entities\Setting;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Http\Middleware\Authorization;
use Modules\Setting\Helpers\Setting as SettingHelper;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Core module specific middleware.
     *
     * @var array
     */
    protected $middleware = [
        'can'                     => Authorization::class,
        'locale_session_redirect' => LocaleSessionRedirect::class,
        'localization_redirect'   => LaravelLocalizationRedirectFilter::class,
        'localize'                => LaravelLocalizationRoutes::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupSupportedLocales();
        $this->registerSetting();
        $this->setupAppLocale();
        $this->setupAppCacheDriver();
        $this->hideDefaultLocaleInURL();
        $this->setupAppTimezone();
        $this->setupMailConfig();
        $this->registerMiddleware();
    }

    /**
     * Setup supported locales.
     *
     * @return void
     */
    private function setupSupportedLocales()
    {
        $supportedLocales = [];

        foreach ($this->getSupportedLocales() as $locale) {
            $supportedLocales[$locale]['name'] = Locale::name($locale);
        }

        $this->app['config']->set('laravellocalization.supportedLocales', $supportedLocales);
    }

    /**
     * Get supported locales from database.
     *
     * @return array
     */
    private function getSupportedLocales()
    {
        try {
            return Setting::get('supported_locales', [config('app.locale')]);
        } catch (\Exception $e) {
            return [config('app.locale')];
        }
    }

    /**
     * Register setting binding.
     *
     * @return void
     */
    private function registerSetting()
    {
        $this->app->singleton('setting', function () {
            return new SettingHelper(Setting::allCached());
        });
    }

    /**
     * Setup application locale.
     *
     * @return void
     */
    private function setupAppLocale()
    {
        $this->app['config']->set('app.locale', $defaultLocale = Setting::get('default_locale'));
        $this->app['config']->set('app.fallback_locale', $defaultLocale);

        $locale = is_null(LaravelLocalization::setLocale()) ? $defaultLocale : null;

        LaravelLocalization::setLocale($locale);
    }

    /**
     * Setup application cache driver.
     *
     * @return void
     */
    private function setupAppCacheDriver()
    {
        $this->app['config']->set('cache.default', config('app.cache') ? 'file' : 'array');
    }

    /**
     * Hide default locale in url for non multi-locale mode.
     *
     * @return void
     */
    private function hideDefaultLocaleInURL()
    {
        if (! is_multilingual()) {
            $this->app['config']->set('laravellocalization.hideDefaultLocaleInURL', true);
        }
    }

    /**
     * Setup application timezone.
     *
     * @return void
     */
    private function setupAppTimezone()
    {
        $timezone = setting('default_timezone') ?? config('app.timezone');

        date_default_timezone_set($timezone);

        $this->app['config']->set('app.timezone', $timezone);
    }

    /**
     * Setup application mail config.
     *
     * @return void
     */
    private function setupMailConfig()
    {
        $this->app['config']->set('mail.default', 'smtp');
        $this->app['config']->set('mail.from.address', setting('mail_from_address'));
        $this->app['config']->set('mail.from.name', setting('mail_from_name'));
        $this->app['config']->set('mail.mailers.smtp.host', setting('mail_host'));
        $this->app['config']->set('mail.mailers.smtp.port', setting('mail_port'));
        $this->app['config']->set('mail.mailers.smtp.username', setting('mail_username'));
        $this->app['config']->set('mail.mailers.smtp.password', setting('mail_password'));
        $this->app['config']->set('mail.mailers.smtp.encryption', setting('mail_encryption'));
    }

    /**
     * Register application middleware.
     *
     * @return void
     */
    private function registerMiddleware()
    {
        foreach ($this->middleware as $name => $middleware) {
            $this->app['router']->aliasMiddleware($name, $middleware);
        }
    }
}
