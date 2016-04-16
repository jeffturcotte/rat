<?php
namespace Rat;

class Identifier
{
    /**
     * The hash/id map
     *
     * @var array
     */
    protected $hashes;


    /**
     * The hash/id map
     *
     * @var array
     */
    protected $objects;


    /**
     * Gets an object from the identity map
     *
     * @param mixed $id
     *     The identifier
     *
     * @return object
     *     The object mapped to the supplied identifier
     **/
    public function get($id)
    {
        if (!isset($this->objects[$id])) {
            return null;
        }

        return $this->objects[$id];
    }


    /**
     * Gets the id of the supplied object
     *
     * @param object $object
     *     The object to identify
     *
     * @return mixed
     *     The id of the object
     */
    public function identify($object)
    {
        $hash = $this->hash($object);

        if (!isset($this->hashes[$hash])) {
            return null;
        }

        return $this->hashes[$hash];
    }


    /**
     * Sets an object within the identity map
     *
     * @param mixed
     *     The id of the object
     *
     * @param object
     *     The object to associate to the id
     */
    public function set($id, $object)
    {
        $hash = $this->hash($object);
        $this->objects[$id] = $object;
        $this->hashes[$hash] = $id;
    }


    /**
     * Hashes the supplied object
     *
     * @param object $object
     *     The object to hash
     *
     * @return string
     *     The object hash
     */
    protected function hash($object)
    {
        return spl_object_hash($object);
    }
}
