<?php
/*
 * This file is part of the behat-registry
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace eBayEnterprise\Behat\RegistryExtension;

class Registry extends \ArrayObject
{
    /**
     * @var Persister
     */
    private $persister;

    /**
     * @param Persister $persister
     */
    public function __construct(Persister $persister = null)
    {
        $this->persister = $persister;
    }

    /**
     * Set object persister.
     *
     * @param Persister $persister
     */
    public function setPersister(Persister $persister)
    {
        $this->persister = $persister;
    }

    /**
     * Find all objects instances of certain type.
     *
     * @param string $type
     * @param int    $offset
     * @param int    $length
     *
     * @return array
     */
    public function find($type = null, $offset = null, $length = null)
    {
        $entities = $this->getArrayCopy();

        if ($type !== null) {
            $entities = array_filter($entities, function ($entity) use ($type) {
                return get_class($entity) == ltrim($type, '\\');
            });
        }

        if ($offset !== null || $length !== null) {
            $entities = array_slice($entities, $offset, $length);
        }

        return $entities;
    }

    /**
     * Merge two registries.
     *
     * @param array|Registry $registry
     *
     * @return $this
     */
    public function merge($registry)
    {
        if ($registry instanceof Registry) {
            $registry = $registry->getArrayCopy();
        }

        $this->exchangeArray(array_merge($this->getArrayCopy(), $registry));

        return $this;
    }

    /**
     * Find first object of certain type.
     *
     * @param string $type
     *
     * @return array
     */
    public function findOne($type = null)
    {
        $entities = $this->find($type, 0, 1);

        return current($entities);
    }

    /**
     * Find last object of certain type.
     *
     * @param string $type
     *
     * @return array
     */
    public function findLast($type = null)
    {
        $entities = $this->find($type, -1);

        return current($entities);
    }

    /**
     * Reset registry storage and remove all entities.
     *
     * @return $this
     */
    public function reset()
    {
        if ($this->persister !== null) {
            $this->remove();
        }

        $this->exchangeArray(array());

        return $this;
    }

    /**
     * Remove all entities from persister.
     */
    private function remove()
    {
        $this->checkPersister();

        $this->persister->beginTransaction();
        foreach ($this as $entity) {
            $this->persister->remove($entity);
        }

        $this->persister->commitTransaction();
    }

    /**
     * Persist all entities from Registry.
     *
     * @return $this
     */
    public function persist()
    {
        $this->checkPersister();

        $this->persister->beginTransaction();
        foreach ($this as $entity) {
            $this->persister->persist($entity);
        }

        $this->persister->commitTransaction();

        return $this;
    }

    /**
     * Reload all entities from Registry.
     *
     * @return $this
     */
    public function reload()
    {
        $this->checkPersister();

        foreach ($this as $key => $entity) {
            $this[$key] = $this->persister->reload($entity);
        }

        return $this;
    }

    /**
     * Throws an exception if persister is not set.
     */
    private function checkPersister()
    {
        if ($this->persister === null) {
            throw new \InvalidArgumentException('Persister is not defined');
        }
    }
}
