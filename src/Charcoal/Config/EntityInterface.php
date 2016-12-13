<?php

namespace Charcoal\Config;

use \ArrayAccess;
use \JsonSerializable;
use \Serializable;

/**
 *
 */
interface EntityInterface extends
    ArrayAccess,
    JsonSerializable,
    Serializable
{
    /**
     * Get the entity available keys.
     *
     * @return array
     */
    public function keys();

    /**
     * Gets the entity data, as associative array map.
     *
     * @param array $filters Optional. Property filters.
     * @return array The data map.
     */
    public function data(array $filters = null);

    /**
     * Sets the entity data, from associative array map (or any other Traversable).
     *
     * This function takes an array and fill the property with its value.
     *
     * @param array|\Traversable $data The entity data.
     * @return EntityInterface Chainable
     */
    public function setData($data);

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($key)` returning true does not mean that `get($key)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundException`.
     *
     * @param string $key Identifier of the entry to look for.
     * @return boolean
     */
    public function has($key);

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $key Identifier of the entry to look for.
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     * @return mixed Entry.
     * @see ContainerInterface::get()
     */
    public function get($key);

    /**
     * @param string $key Identifier of the entry to set.
     * @param mixed  $val The value to set.
     * @return EntityInterface Chainable
     */
    public function set($key, $val);

    /**
     * @param EntityInterface[] $delegates The list of delegates to add.
     * @return EntityInterface Chainable.
     */
    public function setDelegates(array $delegates);

    /**
     * @param EntityInterface $delegate A config object to add as delegate.
     * @return EntityInterface Chainable
     */
    public function addDelegate(EntityInterface $delegate);

    /**
     * @param EntityInterface $delegate A config object to prepend as delegate.
     * @return EntityInterface Chainable
     */
    public function prependDelegate(EntityInterface $delegate);

    /**
     * @param string $separator The separator character.
     * @return EntityInterface Chainable
     */
    public function setSeparator($separator);
}