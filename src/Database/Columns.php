<?php

/**
 * @package MyFramework
 * @subpackage Database
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Database;

/**
 * Configura as colunas selecionadas de uma consulta SQL.
 */
class Columns
{
    /**
     * Colunas selecionadas.
     *
     * @var array
     */
    protected $aColumns = [];

    /**
     * Inicializa as colunas selecionadas.
     *
     * @param string $sSchema
     * @param string $sTable
     * @param array $aColumns
     */
    public function __construct($sSchema, $sTable, $aColumns)
    {
        foreach ($aColumns as $sColumn) {
            $this->aColumns[] = "{$sSchema}.{$sTable}.{$sColumn}";
        }
    }

    /**
     * Retorna as colunas numa string separadas por vírgula.
     *
     * @return array
     */
    public function getColumns()
    {
        return implode(', ', $this->aColumns);
    }

}
