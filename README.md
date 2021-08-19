# WP Workhorse

## Usage

#### Implement Plugin directory structure.

Copy **example-plugin** directory to **mu-plugins** or **plugins** directory.

Make necessary customizations according to your needs. 

#### Implement in the plugin root file (Eg: *`plugin-root.php`*).

```php
use Workhorse\Plugin\Bootloader;

if (!defined('ABSPATH')) {
    exit;
}

Bootloader::register(__FILE__, \PalluaClinic\PalluaClinicCore::PLUGIN_NAME);

```
