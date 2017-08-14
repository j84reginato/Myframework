<?php

namespace models;

class Object
{
    public function __construct($method = null, $all = true)
    {
        if ($method == 'POST') {
            foreach ($_POST as $key => $value) {
                $this->$key = filter_input(INPUT_POST, $key, FILTER_SANITIZE_MAGIC_QUOTES);
            }
        }
        if (isset($_FILES)) {
            foreach ($_FILES as $key => $value) {
                if ($all || isset($this->$key)) {
                    $this->$key = $value;
                }
            }
        }
    }

}
