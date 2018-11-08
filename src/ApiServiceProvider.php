<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 05/11/2018
 * Time: 11:56
 */

namespace Z1lab\JsonApi;


use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerTranslations();
    }

    /**
     * Register the config file
     *
     * @return void
     */
    public function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/json.api.php' => config_path('json.api.php')
        ], 'json-api-config');

        $this->mergeConfigFrom(__DIR__ . '/../config/json.api.php', 'json_api');
    }

    /**
     * Register the translations files
     */
    public function registerTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'json_api');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/json_api'),
        ]);
    }
}