<?php

namespace Myframework\Database;

use Myframework\Entities\Entity;

interface MapperInterface
{
    public function load($id);
    public function store(Entity $entity);
    public function delete(Entity $entity);
}
