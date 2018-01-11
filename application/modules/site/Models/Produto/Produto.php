<?php

namespace Application\Site\Models;

use Myframework\Entities\Entity;
use Exception;

/**
 * Define a Entity Produto.
 *
 * @author Jonatan Noronha Reginato
 */
class Produto extends Entity
{
    /**
     * @param integer $estoque
     * @throws Exception
     */
    public function setEstoque($estoque)
    {
        if (is_numeric($estoque) && $estoque > 0) {
            $this->estoque = $estoque;
        } else {
            throw new Exception("Estoque {$estoque} inválido em " . __CLASS__);
        }
    }
}
