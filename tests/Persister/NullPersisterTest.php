<?php

namespace eBayEnterprise\Behat\RegistryExtensionTest\Persister;

use eBayEnterprise\Behat\RegistryExtension\Persister\NullPersister;

class NullPersisterTest extends \PHPUnit_Framework_TestCase
{
    public function testPersist()
    {
        $entity = new \stdClass();

        $persister = new NullPersister();
        $persister->persist($entity);
    }

    public function testReload()
    {
        $entity = new \stdClass();

        $persister = new NullPersister();

        $this->assertSame($entity, $persister->reload($entity));
    }

    public function testTransactional()
    {
        $persister = new NullPersister();

        $persister->beginTransaction();
        $persister->commitTransaction();
    }

    public function testRemove()
    {
        $entity = new \stdClass();

        $persister = new NullPersister();
        $persister->remove($entity);
    }
}
