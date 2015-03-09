<?php
/*
 * This file is part of the behat-registry
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace eBayEnterprise\Behat\RegistryExtension;

interface Persister
{
    /**
     * Begin transaction.
     */
    public function beginTransaction();

    /**
     * Persist an object.
     *
     * @param object $entity
     */
    public function persist($entity);

    /**
     * Remove an object.
     *
     * @param object $entity
     */
    public function remove($entity);

    /**
     * Reload a single entity.
     * Return a new instance of the entity.
     *
     * @param object $entity
     *
     * @return mixed
     */
    public function reload($entity);

    /**
     * Commit transaction.
     */
    public function commitTransaction();
}
