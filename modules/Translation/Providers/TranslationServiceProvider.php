<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Translation\Providers;

use Illuminate\Support\Carbon;
use Astrotomic\Translatable\Locales;
use Illuminate\Translation\Translator;
use Illuminate\Support\ServiceProvider;
use Modules\Translation\Helpers\TranslationLoader;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLoader();
        $this->registerTranslator();
    }

    /**
     * Get file and database translations.
     *
     * @return void
     */
    private function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoader($app['files'], $app['path.lang']);
        });
    }

    /**
     * Register translator.
     *
     * @return void
     */
    private function registerTranslator()
    {
        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];
            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale(locale());

        $this->setupTranslatable();
    }

    /**
     * Setup translatable.
     *
     * @return void
     */
    private function setupTranslatable()
    {
        $this->app['config']->set('translatable.use_fallback', true);
        $this->app['config']->set('translatable.fallback_locale', setting('default_locale'));
        $this->app['config']->set('translatable.locales', supported_locale_keys());

        $this->app->singleton('translatable.locales', Locales::class);
        $this->app->singleton(Locales::class);
    }
}
