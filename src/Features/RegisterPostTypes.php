<?php


namespace Workhorse\Features;


use Illuminate\Support\Collection;
use Workhorse\Application;
use Workhorse\Contracts\PostType;

/**
 * Class RegisterPostTypes
 * @package Workhorse\Features
 */
class RegisterPostTypes
{
    /**
     * Bootstrap the given application.
     *
     * @param \Workhorse\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {

            $postTypes = Collection::make($app->config['plugin.post_types']);

            $postTypes->each(function (string $postTypeClass) {
                /** @var PostType $postType */
                $postType = new $postTypeClass();

                $postType->register();
            });
    }
}