<?php


if (!function_exists('function_not_exists')) {
    function function_not_exists(string $name): bool
    {
        return !function_exists($name);
    }
}

if (function_not_exists('setup_workhorse')) {
    function setup_workhorse(\Composer\Composer $composer)
    {
        $basepath = getcwd();
        $vendorDir =  $composer->getConfig()->get('vendor-dir');

        echo "Installing workhorse plugin." . PHP_EOL;
        \copy(
            $vendorDir . '/rogue-one/workhorse/workhorse-plugin.php',
            $basepath . '/web/app/mu-plugins/workhorse-plugin.php',
        );
    }
}

if (function_not_exists('init_workhorse')) {
    function init_workhorse()
    {
    }
}
