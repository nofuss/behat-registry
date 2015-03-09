<?php

namespace eBayEnterprise\Behat\RegistryExtensionTest;

use eBayEnterprise\Behat\RegistryExtension\Registry;
use eBayEnterprise\Behat\RegistryExtensionTest\Types\TypeOne;
use eBayEnterprise\Behat\RegistryExtensionTest\Types\TypeTwo;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testPersist()
    {
        $firstEntity = new \stdClass();
        $secondEntity = new \stdClass();

        $persister = $this->getMock('\eBayEnterprise\Behat\RegistryExtension\Persister');
        $persister->expects($this->at(0))
            ->method('beginTransaction');

        $persister->expects($this->at(1))
            ->method('persist')
            ->with($firstEntity);

        $persister->expects($this->at(2))
            ->method('persist')
            ->with($secondEntity);

        $persister->expects($this->at(3))
            ->method('commitTransaction');

        $registry = new Registry($persister);
        $registry->append($firstEntity);
        $registry->append($secondEntity);
        $registry->persist();
    }

    public function testPersistWithoutPersister()
    {
        $this->setExpectedException('InvalidArgumentException');

        $registry = new Registry();
        $registry->append(new \stdClass());
        $registry->persist();
    }

    public function testClean()
    {
        $firstEntity = new \stdClass();
        $secondEntity = new \stdClass();

        $persister = $this->getMock('\eBayEnterprise\Behat\RegistryExtension\Persister');
        $persister->expects($this->at(0))
            ->method('beginTransaction');

        $persister->expects($this->at(1))
            ->method('remove')
            ->with($firstEntity);

        $persister->expects($this->at(2))
            ->method('remove')
            ->with($secondEntity);

        $persister->expects($this->at(3))
            ->method('commitTransaction');

        $registry = new Registry($persister);
        $registry->append($firstEntity);
        $registry->append($secondEntity);
        $registry->reset();
    }

    public function testCleanWithoutPersister()
    {
        $registry = new Registry();
        $registry->append(new \stdClass());
        $registry->reset();

        $this->assertEquals(array(), $registry->getArrayCopy());
    }

    public function testReload()
    {
        $oldEntity = new \stdClass();
        $newEntity = new \stdClass();

        $persister = $this->getMock('\eBayEnterprise\Behat\RegistryExtension\Persister');
        $persister->expects($this->once())
            ->method('reload')
            ->with($oldEntity)
            ->willReturn($newEntity);

        $registry = new Registry($persister);
        $registry->append($oldEntity);
        $registry->reload();

        $this->assertSame($newEntity, $registry->findOne(get_class($oldEntity)));
    }

    public function testMergeWithArray()
    {
        $firstEntity = new \stdClass();
        $secondEntity = new \stdClass();

        $registry = new Registry();
        $registry->append($firstEntity);
        $registry->merge(array($secondEntity));

        $this->assertSame(array($firstEntity, $secondEntity), $registry->getArrayCopy());
    }

    public function testMergeWithRegistry()
    {
        $firstEntity = new \stdClass();
        $secondEntity = new \stdClass();

        $firstRegistry = new Registry();
        $firstRegistry->append($firstEntity);

        $secondRegistry = new Registry();
        $secondRegistry->append($secondEntity);

        $firstRegistry->merge($secondRegistry);

        $this->assertSame(array($firstEntity, $secondEntity), $firstRegistry->getArrayCopy());
    }

    public function testFindAll()
    {
        $entities = array(new \stdClass(), new \stdClass(), new \stdClass());

        $registry = new Registry();
        $registry->merge($entities);

        $this->assertSame($entities, $registry->getArrayCopy());
    }

    public function testFindByType()
    {
        $entities = array('one' => new TypeOne(), 'two' => new TypeTwo(), 'three' => new TypeOne());
        $expected = array('one' => $entities['one'], 'three' => $entities['three']);

        $registry = new Registry();
        $registry->merge($entities);

        $this->assertEquals($expected, $registry->find(get_class(reset($expected))));
    }

    public function testFindSlice()
    {
        $entities = array('one' => new TypeOne(), 'two' => new TypeTwo(), 'three' => new TypeOne());
        $expected = array('two' => $entities['two']);

        $registry = new Registry();
        $registry->merge($entities);

        $this->assertEquals($expected, $registry->find(null, -2, 1));
    }

    public function testFindLast()
    {
        $expected = new TypeOne();
        $entities = array('one' => new TypeOne(), 'two' => new TypeTwo(), 'three' => $expected);

        $registry = new Registry();
        $registry->merge($entities);

        $this->assertEquals($expected, $registry->findLast());
    }

    public function testFindOne()
    {
        $expected = new TypeOne();
        $entities = array('one' => $expected, 'two' => new TypeTwo(), 'three' => new TypeOne());

        $registry = new Registry();
        $registry->merge($entities);

        $this->assertEquals($expected, $registry->findOne());
    }

    public function testFindLastByType()
    {
        $expected = new TypeOne();
        $entities = array(new TypeOne(), new TypeTwo(), $expected, new TypeTwo());

        $registry = new Registry();
        $registry->merge($entities);

        $this->assertSame($expected, $registry->findLast(get_class($expected)));
    }

    public function testFindOneByType()
    {
        $expected = new TypeOne();
        $entities = array($expected, new TypeTwo(), new TypeOne(), new TypeTwo());

        $registry = new Registry();
        $registry->merge($entities);

        $this->assertSame($expected, $registry->findOne(get_class($expected)));
    }
}
