<?php
namespace Rat;

trait EntityTrait
{
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function getEntityType()
    {
        return get_class($this);
    }

    public function jsonSerialize()
    {
        return $this->export();
    }

    public function export()
    {
        return $this->data;
    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
}

