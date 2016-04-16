<?php
namespace Rat;

use PDO;

class Storage
{
    public function __construct(Identifier $identifier, PDO $conn)
    {
        $this->identifier = $identifier;
        $this->conn       = $conn;
    }

    /**
     * Retrieve an entity from storage
     *
     * @param string $type
     *     The type of entity to retrieve
     *
     * @param string $id
     *     The id of the entity
     *
     * @return null|EntityInterface
     *     null if entity not found, otherwise entity
     */
    public function get($type, $id)
    {
        if ($object = $this->identifier->get($id)) {
            return $object;
        }

        $query = "
            SELECT
                *
            FROM
                objects
            WHERE
                type = :type AND
                id = :id
        ";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':type', $type);
        $statement->bindParam(':id', $id);
        $statement->execute();

        $obj = $statement->fetch(PDO::FETCH_ASSOC);

        if ($obj === null) {
            return null;
        }

        $entity = new $type(json_decode($obj['data'], true));

        $this->identifier->set($id, $entity);

        return $entity;
    }

    /**
     * Save an entity to the database
     *
     * @param Rat\EntityInterface $entity
     *     The entity to save in the db
     *
     * @return boolean
     *     Whether or not the entity was successfully saved
     */
    public function save(EntityInterface $entity)
    {
        $type = $entity->getEntityType();
        $data = array_filter($entity->export());
        $data = json_encode($data);

        if ($id = $this->identifier->identify($entity)) {
            $query = "
                UPDATE
                    objects
                SET
                    type = :type,
                    data = :data
                WHERE
                    id = :id
            ";
        } else {
            $id = md5($type . $data);

            $query = "
                INSERT INTO objects (
                    id,
                    type,
                    data
                ) VALUES (
                    :id,
                    :type,
                    :data
                )
            ";
        }

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->bindParam(':type', $type);
        $statement->bindParam(':data', $data);
        $statement->execute();

        $this->identifier->set($id, $entity);

        return $id;
    }
}
