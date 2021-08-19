# WP Workhorse

**Note:-** This package is dependent on the WordPress polylang plugin to provide multilingual support.

## Usage

#### Implement Plugin directory structure.

Copy **example-plugin** directory to **mu-plugins** or **plugins** directory.

Make necessary customizations to the boilerplate according to your needs. 

#### Implement in the plugin root file (Eg: *`plugin-root.php`*).

```php
use Workhorse\Plugin\Bootloader;

if (!defined('ABSPATH')) {
    exit;
}

Bootloader::register(__FILE__, \PalluaClinic\PalluaClinicCore::PLUGIN_NAME);

```

## TODO

- [ ] Implement command to generate plugin boilerplate.