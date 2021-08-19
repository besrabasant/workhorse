<?php

namespace Workhorse;

use Composer\Script\Event;

class ComposerScripts
{
    public static function postAutoloadDump(Event $event): void
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        require $vendorDir . '/rogue-one/workhorse/workhorse.php';
        \setup_workhorse($event->getComposer());
    }
}
