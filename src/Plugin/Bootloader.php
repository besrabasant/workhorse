<?php

namespace Workhorse\Plugin;

use Workhorse\Application;
use Workhorse\PluginRepository;
use Illuminate\Filesystem\Filesystem;

class Bootloader
{
    /** @var \Workhorse\Application */
    private $app;

    /** @var string */
    private $plugin_file;

    /** @var string */
    private $plugin_name;

    /** @var string */
    private $plugin_base_path;

    /**
     * The feature classes for the application.
     *
     * @var array
     */
    protected $coreFeatures = [
        \Workhorse\Features\LoadConfiguration::class,
        \Workhorse\Features\RegisterProviders::class,
        \Workhorse\Features\RegisterPostTypes::class,
        \Workhorse\Features\RegisterTaxonomies::class,
        \Workhorse\Features\RegisterAcfFieldGroups::class,
    ];

    /**
     * Constructor
     *
     * @param string $plugin_file
     * @param string $plugin_name
     */
    public function __construct(string $plugin_file, string $plugin_name = null)
    {
        $filesystem = new Filesystem();
        $this->plugin_file = $plugin_file;
        $this->plugin_name = $plugin_name ?: $filesystem->name($this->plugin_file);
        $this->plugin_base_path = $filesystem->dirname($this->plugin_file);

        $this->init();
    }

    public static function register(string $plugin_file, string $plugin_name = null)
    {
        $instance = new static($plugin_file, $plugin_name);

        $instance->boot();

        return $instance;
    }

    public function init(): self
    {
        $this->app = new Application($this->plugin_name, $this->plugin_base_path);

        PluginRepository::add($this->plugin_name, $this->app);

        return $this;
    }

    /**
     * Get the feature classes for the application.
     *
     * @return array
     */
    protected function coreFeatures()
    {
        return $this->coreFeatures;
    }

    public function boot(): void
    {
        if (!$this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->coreFeatures());
        }

        $this->app->boot();
    }

    public function getAppInstance(): Application
    {
        return $this->app;
    }
}
