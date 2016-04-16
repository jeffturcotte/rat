<?php
namespace Rat;

use JsonSerializable;

interface EntityInterface extends JsonSerializable
{
    public function getEntityType();
}
