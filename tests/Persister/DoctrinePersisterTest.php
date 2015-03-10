<?php

namespace eBayEnterprise\Behat\RegistryExtensionTest\Persister;

use eBayEnterprise\Behat\RegistryExtension\Persister\DoctrinePersister;

class DoctrinePersisterTest extends \PHPUnit_Framework_TestCase
{
    public function testPersist()
    {
        $entity = new \stdClass();
        $manager = $this->getEntityManagerMock();
        $manager->expects($this->once())
            ->method('persist')
            ->with($entity);

        $persister = new DoctrinePersister($manager);
        $persister->persist($entity);
    }

    public function testReload()
    {
        $oldEntity = new \stdClass();
        $newEntity = new \stdClass();

        $repository = $this->getEntityRepositoryMock();
        $repository->expects($this->once())
            ->method('find')
            ->with($oldEntity)
            ->willReturn($newEntity);


        $manager = $this->getEntityManagerMock();
        $manager->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);
        $manager->expects($this->once())
            ->method('detach')
            ->with($oldEntity);


        $persister = new DoctrinePersister($manager);
        $entity = $persister->reload($oldEntity);

        $this->assertSame($newEntity, $entity);
    }

    public function testTransactional()
    {
        $manager = $this->getEntityManagerMock();
        $manager->expects($this->once())
            ->method('flush');

        $persister = new DoctrinePersister($manager);
        $persister->beginTransaction();
        $persister->commitTransaction();
    }

    public function testRemove()
    {
        $entity = new \stdClass();
        $manager = $this->getEntityManagerMock();
        $manager->expects($this->once())
            ->method('remove')
            ->with($entity);

        $persister = new DoctrinePersister($manager);
        $persister->remove($entity);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Doctrine\ORM\EntityManager
     */
    private function getEntityManagerMock()
    {
        return $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Doctrine\ORM\EntityRepository
     */
    private function getEntityRepositoryMock()
    {
        return $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
                ->disableOriginalConstructor()
                ->getMock();
    }
}
