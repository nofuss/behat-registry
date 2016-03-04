<?php
/*
 * This file is part of the behat-registry
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace eBayEnterprise\Behat\RegistryExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use eBayEnterprise\Behat\RegistryExtension\Context\RegistryAwareContext;
use eBayEnterprise\Behat\RegistryExtension\Registry;

class RegistryAwareInitializer implements ContextInitializer
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function initializeContext(Context $context)
    {
        if (!$context instanceof RegistryAwareContext) {
            return;
        }

        $context->setRegistry($this->registry);
    }
}
