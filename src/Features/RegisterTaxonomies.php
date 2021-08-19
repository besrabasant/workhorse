<?php


namespace Workhorse\Features;


use Illuminate\Support\Collection;
use Workhorse\Application;
use Workhorse\Contracts\PostType;
use Workhorse\Contracts\Taxonomy;

/**
 * Class RegisterTaxonomies
 * @package Workhorse\Features
 */
class RegisterTaxonomies
{
    /**
     * Bootstrap the given application.
     *
     * @param \Workhorse\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
            $taxonomies = Collection::make($app->config['plugin.taxonomies']);

            $taxonomies->each(function (string $taxonomyClass) {
                /** @var Taxonomy $taxonomy */
                $taxonomy = new $taxonomyClass();

                $taxonomy->register();
            });

    }
}