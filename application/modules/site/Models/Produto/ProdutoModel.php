<?php

namespace Application\Site\Models;

use Myframework\Database\Mapper;
use Myframework\Database\ConnectionConfig;

class ProdutoModel extends Mapper
{
    const ENTITY_NAMESPACE = Application\Site\Models\Produto;
    const ENTITY_TABLENAME = ConnectionConfig::DB_PREFIX . 'produto';
}
