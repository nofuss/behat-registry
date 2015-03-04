<?php

namespace eBayEnterprise\Behat\RegistryExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use eBayEnterprise\Behat\RegistryExtension\Registry;

class RawRegistryContext implements Context, RegistryAwareContext
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * Inject Registry instance.
     *
     * @param Registry $registry
     */
    public function setRegistry(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return Registry
     */
    protected function getRegistry()
    {
        return $this->registry;
    }

    /**
     * Clean up after scenario was executed.
     *
     * @AfterScenario
     *
     * @param AfterScenarioScope $scope
     */
    public function afterScenarioCleanup(AfterScenarioScope $scope)
    {
        $this->registry->reset();
    }
}
