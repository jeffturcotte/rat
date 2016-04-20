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
    }

    public function get($type, $id)
    {
        return $this->storage->get($type, $id);
    }

    public function save(EntityInterface $entity)
    {
        $this->storage->save($entity);
    }

    public function identify(EntityInterface $entity)
    {
        $this->storage->identify($entity);
    }


    private function find($type, $conditions, $values)
    {
        // '(([^\s]+) ([=<>]+)|(IN)) :';

        $query = "SELECT * FROM objects WHERE type = :type AND data->>:property = :value";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':type', $type);

        foreach($values as $placeholder => $value) {
        $statement->bindParam(':property', array_keys($conditions)[0]);
        $statement->bindParam(':value', array_values($conditions)[0]);
        $statement->execute();

        $obj = $statement->fetch(PDO::FETCH_ASSOC);

        return new $type(json_decode($obj['data']));
    }
}
