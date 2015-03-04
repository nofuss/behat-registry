<?php

namespace eBayEnterprise\Behat\RegistryExtension\Context;

use eBayEnterprise\Behat\RegistryExtension\Registry;

interface RegistryAwareContext
{
    /**
     * Inject Registry instance.
     *
     * @param Registry $registry
     */
    public function setRegistry(Registry $registry);
}
