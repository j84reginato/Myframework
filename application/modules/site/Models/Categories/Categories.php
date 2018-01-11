<?php

namespace KdDoctor\classes;

class Categories
{
    private $parent_node;
    private $children = array();    
    public $category_list;
    
    /**
     * getParentNode
     * 
     * Retorna o valor do atributo Categories->parent_node
     * 
     * @global \KdDoctor\classes\DatabasePdo $objDb
     * @param str $table
     * @param int $category_id
     * @return array
     */
    public function getParentNode($table, $category_id)
    {
        global $objDb;

        $query = "SELECT right_id, left_id FROM " . DB_PREFIX . $table . " WHERE id = :id";
        $params[] = array(':id', $category_id, 'int');
        $objDb->query($query, $params);
        $this->parent_node = $objDb->result();
        return $this->parent_node;
      }

    /**
     * getChildrenList
     * 
     * Retorna o valor do atributo Categories->children
     * 
     * @global \KdDoctor\classes\DatabasePdo $objDb
     * @param int $left_id
     * @param int $right_id
     * @param str $table
     * @param str $field
     * @return array
     */
    public function getChildrenList($left_id, $right_id, $table, $field = 'id')
    {
        global $objDb;

        if (empty($left_id) || empty($right_id)) {
            return $this->children;
        }
        $query = "SELECT " . $field . " FROM " . DB_PREFIX . $table . " WHERE left_id > :left_id AND right_id < :right_id";
        $params[] = array(':left_id', $left_id, 'int');
        $params[] = array(':right_id', $right_id, 'int');
        $objDb->query($query, $params);
        while ($child = $objDb->fetch()) {
            $this->children = $child;
        }
        return $this->children;
    }




























    // Add an element to the tree as a child of $parent and as $child_num'th child.
    // If $data is not supplied the insert id will be returned.
    function add($parent_id, $child_num = 0, $misc_data = false)
    {
        global $objDb;

        // Trabalha no elemento pai
        // Retorna false se o elemento pai for negativo ou não numérico
        if (!is_numeric($parent_id) || $parent_id < 0) {
            return false;
        }
        // Se o id do elemento pai for indicado e este for válido
        if ($parent_id != 0) {
            $query = "  SELECT
                            left_id,
                            right_id,
                            level
                        FROM
                            " . DB_PREFIX . "med_spcts
                        WHERE
                            id = :parent_id";

            $params[] = array(':parent_id', $parent_id, 'int');
            $objDb->query($query, $params);

            /* Se o elemento pai informado não for encontrado no bd,
             * retorna false
             */
            if ($objDb->numRows() != 1) {
                return false;
            }
            // aloca o id do elemento pai na variável $parent
            $parent = $objDb->result();

        /* Se não for indicado o id do elemento pai,
         * gera um array através do método getVirtualRoot
         */
        } else {
            $parent = $this->getVirtualRoot();
        }

        // Trabalha no elemento filho
        // Aloca um array de elementos filhos na variável $children
        $children = $this->getChildren(
            $parent['left_id'],
            $parent['right_id'],
            $parent['level']
        );

        if (count($children) == 0) {
            $child_num = 0;
        }
        if ($child_num == 0 || (count($children) - $child_num) <= 0 || (count($children) + $child_num + 1) < 0) {
            $boundry = array('left_id', 'right_id', $parent['left_id']);
        } elseif ($child_num != 0) {
            if ($child_num < 0) {
                $child_num = count($children) + $child_num + 1;
            }
            if ($child_num > count($children)) {
                $child_num = count($children);
            }
            $boundry = array('right_id', 'left_id', $children[$child_num - 1]['right_id']);
        } else {
            return false;
        }

        // Cria um espaço para o novo elemento
        $query = "  UPDATE
                        " . DB_PREFIX . "med_spcts
                    SET
                        left_id = left_id + 2
                    WHERE
                        " . $boundry[0] . " > " . $boundry[2] . " AND
                        " . $boundry[1] . " > " . $boundry[2];

        $objDb->directQuery($query);

        $query = "  UPDATE
                        " . DB_PREFIX . "med_spcts
                    SET
                        right_id = right_id + 2
                    WHERE
                        " . $boundry[1] . " > " . $boundry[2];

        $objDb->directQuery($query);

        // Insere o novo elemento
        $data = array(
            'left_id' => $boundry[2] + 1,
            'right_id' => $boundry[2] + 2,
            'level' => $parent['level'] + 1,
            'parent_id' => $parent_id
        );
        if ($misc_data && is_array($misc_data)) {
            $data = array_merge($misc_data, $data);
        }

        $query = "  INSERT INTO " . DB_PREFIX . "med_spcts
                        (parent_id, left_id, right_id, level, cat_name, cat_colour, cat_image)
                    VALUES
                        (:parent, :left, :right, :level, :name, :colour, :image)";

        $params[] = array(':parent', $data['parent_id'], 'str');
        $params[] = array(':left', $data['left_id'], 'str');
        $params[] = array(':right', $data['right_id'], 'str');
        $params[] = array(':level', $data['level'], 'str');
        $params[] = array(':name', $data['cat_name'], 'str');
        $params[] = array(':colour', $data['cat_colour'], 'str');
        $params[] = array(':image', $data['cat_image'], 'str');
        $objDb->query($query, $params);

        if (!$misc_data) {
            return $objDb->lastInsertId();
        }
        return true;
    }

    // Deletes element $id with or without children. If children should be kept they will become children of $id's parent.
    function delete($id, $keep_children = false)
    {
        global $objSystem, $db_prefix, $objDb;

        if (!defined('IN_SYSTEM')) {
            exit('Access denied');
        }

        if (!is_numeric($id) || $id <= 0 || !is_bool($keep_children)) {
            return false;
        }
        $query = "  SELECT
                        left_id, right_id, level
                    FROM
                        " . DB_PREFIX . "med_spcts
                    WHERE
                        cat_id = :cat_id";

        $params = array();
        $params[] = array(':cat_id', $id, 'int');
        $objDb->query($query, $params);

        if ($objDb->numRows() != 1) { // Row must exist.
            return false;
        }
        $a = $objDb->result();

        if (!$keep_children) {
            // Delete the element with children.
            $query = "  DELETE FROM
                            " . DB_PREFIX . "med_spcts
                        WHERE
                            left_id >= " . $a['left_id'] . " AND
                            right_id <= " . $a['right_id'];

            $objDb->directQuery($query);

            // Remove the hole.
            $diff = $a['right_id'] - $a['left_id'] + 1;
            $query = "  UPDATE
                            " . DB_PREFIX . "med_spcts
                        SET
                            left_id = left_id - " . $diff . "
                        WHERE
                            right_id > " . $a['right_id'] . " AND
                            left_id > " . $a['right_id'];

            $objDb->directQuery($query);
            $query = "  UPDATE
                            " . DB_PREFIX . "med_spcts
                        SET
                            right_id = right_id - " . $diff . "
                        WHERE
                            right_id > " . $a['right_id'];

            $objDb->directQuery($query);
            // No level cahnges needed.
        } else {
            // Delete ONLY the element.
            $query = "  DELETE FROM
                            " . DB_PREFIX . "med_spcts
                        WHERE cat_id = :cat_id";

            $params = array();
            $params[] = array(':cat_id', $id, 'int');
            $objDb->query($query, $params);

            // Fix children.
            $query = "  UPDATE
                            " . DB_PREFIX . "med_spcts
                        SET
                            left_id = left_id - 1,
                            right_id = right_id - 1,
                            level = level - 1
                        WHERE
                            left_id >= " . $a['left_id'] . " AND
                            right_id <= " . $a['right_id'];

            $objDb->directQuery($query);

            // Remove hole.
            $query = "  UPDATE
                            " . DB_PREFIX . "med_spcts
                        SET
                            left_id = left_id - 2
                        WHERE
                            right_id > " . ($a['right_id'] - 1) . " AND
                            left_id > " . ($a['right_id'] - 1);

            $objDb->directQuery($query);

            $query = "  UPDATE
                            " . DB_PREFIX . "med_spcts
                        SET
                            right_id = right_id - 2
                        WHERE
                            right_id > " . ($a['right_id'] - 1);

            $objDb->directQuery($query);
        }
    }

    // Move an element (with children) $id, under element $target_id as the $child_num'th child of that element
    function move($id, $target_id, $child_num = 0)
    {
        global $objSystem, $db_prefix, $objDb;

        if (!defined('IN_SYSTEM')) {
            exit('Access denied');
        }

        if (!is_numeric($id) || !is_numeric($target_id) || !is_numeric($child_num)) {
            return false;
        }
        if ($target_id != 0) {
            $query = "  SELECT
                            left_id, right_id, level
                        FROM
                            " . DB_PREFIX . "med_spcts
                        WHERE
                            cat_id = :cat_id OR
                            cat_id = :target_id";

            // I want the to be returned in order.
            $query .= ' ORDER BY cat_id ' . (($id < $target_id) ? 'ASC' : 'DESC');

            $params = array();
            $params[] = array(':cat_id', $id, 'int');
            $params[] = array(':target_id', $target_id, 'int');
            $objDb->query($query, $params);
            if ($objDb->numRows() != 2) { // Both rows must exist.
                return false;
            }
            $data = $objDb->fetchAll();
            $a = $data[0]; // This is being moved.
            $b = $data[1]; // This is the target.
        } else {
            $query = "SELECT left_id, right_id, level FROM " . DB_PREFIX . "med_spcts WHERE cat_id = :cat_id";
            $params = array();
            $params[] = array(':cat_id', $id, 'int');
            $objDb->query($query, $params);

            if ($objDb->numRows() != 1) { // Row must exist.
                return false;
            }
            $a = $objDb->result(); // This is being moved.
            // Virtual root element.
            $b = $this->getVirtualRoot();
        }

        // We need to get the children.
        $children = $this->getChildren($b['left_id'], $b['right_id'], $b['level']);

        if (count($children) == 0) {
            $child_num = 0;
        }
        if ($child_num == 0 || (count($children) - $child_num) <= 0 || (count($children) + $child_num + 1) < 0) {
            // First child.
            $boundry = array('left_id', 'right_id', 'right_id', $b['left_id']);
        } elseif ($child_num != 0) {
            // Some other child.
            if ($child_num < 0) {
                $child_num = count($children) + $child_num + 1;
            }
            if ($child_num > count($children)) {
                $child_num = count($children);
            }
            $boundry = array('right_id', 'left_id', 'right_id', $children[$child_num - 1]['right_id']);
        } else {
            return false;
        }

        // Math.
        $diff = $a['right_id'] - $a['left_id'] + 1; // The "size" of the tree.

        if ($a['left_id'] < $boundry[3]) {
            $size = $boundry[3] - $diff;
            $dist = $boundry[3] - $diff - $a['left_id'] + 1;
        } else {
            $size = $boundry[3];
            $dist = $boundry[3] - $a['left_id'] + 1;
        }
        // Level math.
        $ldiff = ($a['level'] - $b['level'] - 1) * -1;
        // We have all what we need.

        $query = array();

        // Give the needed rows negative id's.
        $query = "UPDATE " . DB_PREFIX . "med_spcts SET left_id = left_id * -1, right_id = right_id * -1 WHERE left_id >= " . $a['left_id'] . " AND right_id <= " . $a['right_id'];
        $objDb->directQuery($query);
        // Remove the hole.
        $query = "UPDATE " . DB_PREFIX . "med_spcts SET left_id = left_id - " . $diff . " WHERE right_id > " . $a['right_id'] . " AND left_id > " . $a['right_id'];
        $objDb->directQuery($query);
        $query = "UPDATE " . DB_PREFIX . "med_spcts SET right_id = right_id - " . $diff . " WHERE right_id > " . $a['right_id'];
        $objDb->directQuery($query);
        // Add hole
        $query = "UPDATE " . DB_PREFIX . "med_spcts SET left_id = left_id + " . $diff . " WHERE " . $boundry[0] . " > " . $size . " AND " . $boundry[1] . " > " . $size;
        $objDb->directQuery($query);
        $query = "UPDATE " . DB_PREFIX . "med_spcts SET right_id = right_id + " . $diff . " WHERE " . $boundry[2] . " > " . $size;
        $objDb->directQuery($query);
        // Fill hole & update rows & multiply by -1
        $query = "UPDATE " . DB_PREFIX . "med_spcts SET left_id = (left_id - (" . $dist . ")) * -1, right_id = (right_id - (" . $dist . ")) * -1, level = level + (" . $ldiff . ") WHERE left_id < 0";
        $objDb->directQuery($query);
        return true;
    }

    // Copies element $id (with children) to $parent as the $child_mun'th child.
    function copy($id, $parent, $child_num = 0)
    {
        if (!defined('IN_SYSTEM')) {
            exit('Access denied');
        }

        global $objSystem, $db_prefix, $objDb;
        if (!is_numeric($id) || $id < 0 || !is_numeric($parent) || $parent < 0) {
            return false;
        }
        // Get branch left & right id's.
        $query = "SELECT left_id, right_id, level FROM " . DB_PREFIX . "med_spcts WHERE cat_id = :cat_id";
        $params = array();
        $params[] = array(':cat_id', $id, 'int');
        $objDb->query($query, $params);

        if ($objDb->numRows() != 1) { // Row must Exist.
            return false;
        }
        $a = $objDb->result();
        // Get child data.
        $query = "SELECT * FROM " . DB_PREFIX . "med_spcts WHERE left_id >= " . $a['left_id'] . " AND right_id <= " . $a['right_id'];
        $objDb->directQuery($query);
        while ($row = $objDb->fetch()) {
            $data[] = $row;
        }

        if ($parent != 0) {
            $query = "SELECT left_id, right_id, level FROM " . DB_PREFIX . "med_spcts WHERE cat_id = :parent_id";
            $params = array();
            $params[] = array(':parent_id', $parent, 'int');
            $objDb->query($query, $params);

            if ($objDb->numRows() != 1) { // Row must exist.
                return false;
            }
            $b = $objDb->result();
        } else {
            $b = $this->getVirtualRoot();
        }

        // Get target's children.
        $children = $this->getChildren($b['left_id'], $b['right_id'], $b['level']);

        if (count($children) == 0) {
            $child_num = 0;
        }
        if ($child_num == 0 || (count($children) - $child_num) <= 0 || (count($children) + $child_num + 1) < 0) {
            // First child.
            $boundry = array('left_id', 'right_id', 'right_id', $b['left_id']);
        } elseif ($child_num != 0) {
            // Some other child.
            if ($child_num < 0) {
                $child_num = count($children) + $child_num + 1;
            }
            if ($child_num > count($children)) {
                $child_num = count($children);
            }
            $boundry = array('right_id', 'left_id', 'right_id', $children[$child_num - 1]['right_id']);
        } else {
            return false;
        }

        // Math.
        $diff = $a['right_id'] - $a['left_id'] + 1;
        $dist = $boundry[3] - $a['left_id'] + 1;
        // Level math.
        $ldiff = ($a['level'] - $b['level'] - 1);

        // Add hole.
        $query = "UPDATE " . DB_PREFIX . "med_spcts SET left_id = left_id + " . $diff . " WHERE " . $boundry[0] . " > " . $boundry[3] . " AND " . $boundry[1] . " > " . $boundry[3];
        $objDb->directQuery($query);
        $query = "UPDATE " . DB_PREFIX . "med_spcts SET right_id = right_id + " . $diff . " WHERE " . $boundry[2] . " > " . $boundry[3];
        $objDb->directQuery($query);

        // Now we have to insert all the new elements.
        for ($i = 0, $n = count($data); $i < $n; $i++) {
            // We need a new key.
            unset($data[$i][FIELD_KEY]);

            // This fields need new values.
            $data[$i]['left_id'] += $dist;
            $data[$i]['right_id'] += $dist;
            $data[$i]['level'] -= $ldiff;

            $data[$i] = $this->buildSql($data[$i]);
            $query = "INSERT INTO " . DB_PREFIX . "med_spcts SET " . $data[$i];
            $objDb->directQuery($query);
        }
        return true;
    }

    /**
     * getChildren
     *
     * Retorna um array de elementos filhos
     */
    function getChildren($left_id, $right_id, $level)
    {
        global $objDb;
        $children = array();

        $query =  "SELECT * "
                . "FROM " . DB_PREFIX . "med_spcts "
                . "WHERE "
                    . "left_id > :left_id AND "
                    . "right_id < :right_id AND "
                    . "level = :level "
                . "ORDER BY name";

        $params[] = array(':left_id', $left_id, 'int');
        $params[] = array(':right_id', $right_id, 'int');
        $params[] = array(':level', ($level + 1), 'int');
        $objDb->query($query, $params);

        while ($child = $objDb->fetch()) {
            $children[] = $child;
        }
        return $children;
    }

    //returns an ordered list of categories
    function displayTree($left_id, $right_id, $indent = "\t")
    {
        global $db_prefix, $objDb;
        if (!defined('IN_SYSTEM')) {
            exit('Access denied');
        }

        // start with an empty $right stack
        $right = array();
        $return = array();

        // now, retrieve all descendants of the $root node
        $query = "SELECT * FROM " . DB_PREFIX . "med_spcts WHERE left_id > :left_id AND right_id < :right_id ORDER BY left_id ASC";
        $params = array();
        $params[] = array(':left_id', $left_id, 'int');
        $params[] = array(':right_id', $right_id, 'int');
        $objDb->query($query, $params);

        // display each row
        while ($row = $objDb->fetch()) {
            // only check stack if there is one
            if (count($right) > 0) {
                // check if we should remove a node from the stack
                while (isset($right[count($right) - 1]) && $right[count($right) - 1] < $row['right_id']) {
                    array_pop($right);
                }
            }
            // display indented node title
            $return[$row['cat_id']] = str_repeat($indent, count($right)) . $row['cat_name'];
            // add this node to the stack
            $right[] = $row['right_id'];
        }
        return $return;
    }

    /**
     * getVirtualRoot
     *
     * Retorna left_id, right_id e level para o nó virtual
     */
    function getVirtualRoot()
    {
        global $objDb;

        $query = "  SELECT
                        right_id
                    FROM
                        " . DB_PREFIX . "med_spcts
                    ORDER BY
                        right_id DESC
                    LIMIT 1";

        $objDb->directQuery($query);
        $row = $objDb->result();
        $root = array(
            'left_id' => 1,
            'right_id' => $row['right_id'],
            'level' => -1
        );
        return $root;
    }


    // Build INSERT statement
    function buildSql($data)
    {
        if (!defined('IN_SYSTEM')) {
            exit('Access denied');
        }

        foreach ($data as $k => $v) {
            if (is_numeric($v)) {
                $data[$k] = '`' . $k . '` = ' . $v . '';
            } else {
                $data[$k] = '`' . $k . '` = \'' . $v . '\'';
            }
        }
        return implode(', ', $data);
    }

    function checkCategory($id)
    {
        global $objDb;

        $query = "  SELECT
                        id FROM
                        " . DB_PREFIX . "med_spcts
                    WHERE
                        id = :id LIMIT 1";

        $params = array();
        $params[] = array(':id', $id, 'int');
        $objDb->query($query, $params);
        if ($objDb->numRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getCategoryString($selected_category)
    {
        global $objDb;
        global $med_spcts_names;

        if (empty($selected_category) || !isset($selected_category)) {
            return '';
        }

        $query =  "SELECT left_id, right_id, level "
                . "FROM " . DB_PREFIX . "med_spcts "
                . "WHERE id = :id";

        $params[] = array(':id', $selected_category, 'int');
        $objDb->query($query, $params);
        $parent_node = $objDb->result();

        $this->category_list = '';
        $crumbs = $this->getBreadCrumbs($parent_node['left_id'], $parent_node['right_id']);
        for ($i = 0; $i < count($crumbs); $i++) {
            if ($crumbs[$i]['id'] > 0) {
                if ($i > 0) {
                    $this->category_list .= ' &gt; ';
                }
                $this->category_list .= $med_spcts_names[$crumbs[$i]['id']];
            }
        }
        return $this->category_list;
    }

    /**
     * getBreadCrumbs
     *
     */
    function getBreadCrumbs($left_id, $right_id)
    {
        global $objDb;

        if (empty($left_id) || empty($right_id)) {
            return array();
        }

        // Return an array of all parent nodes
        $query =  "SELECT id, name "
                . "FROM " . DB_PREFIX . "med_spcts "
                . "WHERE "
                    . "left_id <= :left_id AND "
                    . "right_id >= :right_id "
                . "ORDER BY "
                    . "left_id ASC";

        $params[] = array(':left_id', $left_id, 'int');
        $params[] = array(':right_id', $right_id, 'int');
        $objDb->query($query, $params);

        $array = array();
        while ($row = $objDb->fetch()) {
            $array[] = $row;
        }
        return $array;
    }

}


