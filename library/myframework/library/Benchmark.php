<?php

namespace sys\core;

class Benchmark {

    public $marker = array();

    public function mark($name)
    {
        $this->marker[$name] = microtime(TRUE);
    }

    public function elapsedTime($point1 = '', $point2 = '', $decimals = 4)
    {
        if ($point1 === '') {
            return '{elapsed_time}';
        }
        if (!isset($this->marker[$point1])) {
            return '';
        }
        if (!isset($this->marker[$point2])) {
            $this->marker[$point2] = microtime(TRUE);
        }
        return number_format($this->marker[$point2] - $this->marker[$point1], $decimals);
    }

    public function memoryUsage()
    {
        return '{memory_usage}';
    }

}
