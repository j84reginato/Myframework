<?php

namespace KdDoctor\classes;

class City {

    private $available_cities;

    /**
     * __construct
     *
     * @global \KdDoctor\classes\DatabasePdo $objDb
     */
    public function __construct()
    {
        global $objDb;

        $query = "SELECT city FROM " . DB_PREFIX . "services GROUP BY city";
        $objDb->directQuery($query);
        while ($row = $objDb->fetch()) {
            $this->available_cities[$row['city']] = $row['city'];
        }
    }

    /**
     * getAvailableCities
     *
     * @return array()
     */
    public function getAvailableCities()
    {
        return $this->available_cities;
    }
}
