<?php


namespace Workhorse\Features;


use Illuminate\Support\Collection;
use Workhorse\Application;
use Workhorse\Contracts\Taxonomy;

/**
 * Class RegisterAcfFieldGroups
 * @package Workhorse\Features
 */
class RegisterAcfFieldGroups
{
    /**
     * Bootstrap the given application.
     *
     * @param \Workhorse\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $fieldGroups = Collection::make($app->config['plugin.acf_field_groups']);

        $fieldGroups->each(function (string $fieldGroupClass) {
            $fieldGroup = new $fieldGroupClass();

            $fieldGroup->register();
        });

    }
}