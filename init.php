<?php
/*
 * This file is part of the behat-registry
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

spl_autoload_register(function($class) {
    if (false !== strpos($class, 'eBayEnterprise\\Behat\\RegistryExtension')) {
        require_once(__DIR__.'/src/'.str_replace('\\', '/', $class).'.php');
        return true;
    }
}, true, false);

return new eBayEnterprise\Behat\RegistryExtension\ServiceContainer\RegistryExtension;
