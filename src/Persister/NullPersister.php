<?php

namespace eBayEnterprise\Behat\RegistryExtension\Persister;

use eBayEnterprise\Behat\RegistryExtension\Persister;

class NullPersister implements Persister
{
    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function persist($entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function remove($entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function reload($entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function commitTransaction()
    {
    }
}
