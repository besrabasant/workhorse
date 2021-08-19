<?php

namespace Workhorse\Features;

use Workhorse\Application;

class RegisterProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Workhorse\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app->registerConfiguredProviders();
    }
}
