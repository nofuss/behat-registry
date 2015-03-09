<?php
/*
 * This file is part of the behat-registry
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace eBayEnterprise\Behat\RegistryExtension\Persister;

use eBayEnterprise\Behat\RegistryExtension\Persister;
use Doctrine\ORM\EntityManager;

class DoctrinePersister implements Persister
{
    /** @var EntityManager */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
        $this->entityManager->persist($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function reload($entity)
    {
        $repository = $this->entityManager->getRepository(get_class($entity));
        $repository->clear($entity);

        return $repository->find($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($entity)
    {
        $this->entityManager->remove($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function commitTransaction()
    {
        $this->entityManager->flush();
    }
}
