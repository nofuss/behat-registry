<?php

namespace eBayEnterprise\Behat\RegistryExtension\Persister;

use Symfony\Component\HttpKernel\KernelInterface;

class SymfonyDoctrinePersister extends DoctrinePersister
{
    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $container = $kernel->getContainer();

        parent::__construct($container->get('doctrine.orm.default_entity_manager'));
    }
}
