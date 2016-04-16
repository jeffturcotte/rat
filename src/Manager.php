<?php
namespace Rat;

use JsonSerializable;
use PDO;

class Manager
{
    protected $storage;
    protected $mapper;

    public function __construct($storage)
    {
        $this->storage = $storage;

        //$this->mapper  = new IdentityMapper();
        //$this->storage = new Storage();
    }

    public function get($type, $id)
    {
        return $this->storage->get($type, $id);
    }

    public function find($type, $conditions = [])
    {
        $query = "SELECT * FROM objects WHERE type = :type AND data->>:property = :value";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':type', $type);
        $statement->bindParam(':property', array_keys($conditions)[0]);
        $statement->bindParam(':value', array_values($conditions)[0]);
        $statement->execute();

        $obj = $statement->fetch(PDO::FETCH_ASSOC);

        return new $type(json_decode($obj['data']));
    }

    public function save(EntityInterface $entity)
    {
        $this->storage->save($entity);
    }

    public function identify(EntityInterface $entity)
    {
        $this->storage->identify($entity);
    }
}
