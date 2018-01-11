<?php

namespace sys\core;

class Dates
{
    private $time_left;
    private $formated_date;

    /**
     * getTimeLeft
     */
    private function getTimeLeft() {
        return $this->time_left;
    }

    /**
     * setTimeLeft
     */
    private function setTimeLeft($tl) {
        $this->time_left = $tl;
    }

    /**
     * getFormatedDate
     */
    private function getFormatedDate() {
        return $this->formated_date;
    }

    /**
     * setFormatedDate
     */
    private function setFormatedDate($fd) {
        $this->formated_date = $fd;
    }

        /**
     * getLeftSeconds
     *
     * d : Day of the month, 2 digits with leading zeros (01 to 31)
     * j : Day of the month without leading zeros (1 to 31)
     * n : Numeric representation of a month, without leading zeros (1 to 12)
     * Y : A full numeric representation of a year, 4 digits (2017)
     * G : 24-hour format of an hour without leading zeros (0 through 23)
     * i : Minutes with leading zeros (00 to 59)
     * s : Seconds, with leading zeros (00 through 59)
     *
     * First calculates the last day of the month
     * then calculates the number of days left to the end of the month
     * and then claculates the seconds left to the end of the month
     */
    public function getLeftSeconds()
    {
        $today = explode('|', date('j|n|Y|G|i|s'));
        $current_day = $today[0];
        $current_month = $today[1];
        $current_year = $today[2];
        $last_day = 31;
        while (!checkdate($current_month, $last_day, $current_year)) {
            $last_day--;
        }
        $days_left = intval($last_day - $current_day);
        $hours_left = 24 - $today[3];
        $mins_left = 60 - $today[4];
        $secs_left = 60 - $today[5];
        $this->setTimeLeft($secs_left + ($mins_left * 60) + ($hours_left * 3600) + ($days_left * 86400));
        return $this->getTimeLeft();
    }

    /**
     * formatDate
     */
    public function formatDate($date, $spacer = '/', $gmt = true)
    {
        global $objSystem;
        global $objTimezone;

        if (!$gmt) {
            $date += $objTimezone->tdiff;
        }
        if ($objSystem->settings['dates_format'] == 'USA') {
            $this->setFormatedDate(date('m' . $spacer . 'd' . $spacer . 'Y', $date));
        } else {
            $this->setFormatedDate(date('d' . $spacer . 'm' . $spacer . 'Y', $date));
        }
        return $this->getFormatedDate();
    }

    /**
     * formatTimeStamp
     */
    public function formatTimeStamp($dt, $spacer = '-')
    {
        global $objSystem;

        $date = explode($spacer, $dt);
        if ($objSystem->settings['dates_format'] == 'USA') {
            $this->setFormatedDate(mktime(0, 0, 0, $date[0], $date[1], $date[2]));
        } else {
            $this->setFormatedDate(mktime(0, 0, 0, $date[1], $date[0], $date[2]));
        }
        return $this->getFormatedDate();
    }

    /**
     * formatTimeLeft
     */
    public function formatTimeLeft($diff)
    {
        global $msg;
        $show_seconds = false;
        $time_left = '';

        $diff_in_days = floor($diff / 86400);
        $rest_a = $diff % 86400;

        $diff_in_hours = floor($rest_a / 3600);
        $rest_b = $rest_a % 3600;

        $diff_in_minutes = floor($rest_b / 60);
        $rest_c = $rest_b % 60;

        $diff_in_secs = $rest_c;

        if ($diff_in_days > 0) {
            $time_left = $diff_in_days . 'd ';
        }

        if (($diff_in_hours > 0) || ($diff_in_hours == 0 && $diff_in_days > 0)) {
            $time_left .= $diff_in_hours . 'h ';
        } else {
            $show_seconds = true;
        }

        if ($diff_in_minutes > 0) {
            $time_left .= $diff_in_minutes . 'm ';
        }

        if ($show_seconds) {
            $time_left .= $diff_in_secs . 's ';
        }

        if ($diff < 0) {
            $time_left = $msg['0201'];
        }
        if (($diff * 60) < 15) {
            $time_left = '<span style="color:#FF0000;">' . $time_left . '</span>';
        }

        $this->setTimeLeft($time_left);
        return $this->getTimeLeft();
    }

    /**
     * actualDate
     *
     * Date and time hanling method
     */
    public function actualDate()
    {
        global $objTimezone;
        return date('M d, Y H:i:s', $objTimezone->ctime);
    }

    /**
     * arrangeDateNoCorrection
     *
     * Date and time hanling method
     */
    public function arrangeDateNoCorrection($date)
    {
        global $objSystem;
        global $msg;

        $mth = 'MON_0' . date('m', $date);
        if ($objSystem->settings['dates_format'] == 'USA') {
            $return = $msg[$mth] . ' ' . date('d, Y - H:i', $date);
        } else {
            $return = date('d', $date) . ' ' . $msg[$mth] . ', ' . date('Y - H:i', $date);
        }
        return $return;
    }

    /**
     * makeTime
     */
    public function makeTime($hr, $min, $sec, $mon, $day, $year)
    {
        global $objSystem;

        if ($objSystem->settings['dates_format'] != 'USA') {
            $mon_ = $mon;
            $mon = $day;
            $day = $mon_;
        }
        return mktime($hr, $min, $sec, $mon, $day, $year);
    }

    /**
     * filterDate
     *
     * Filters date format and date.
     * Changes dd.mm.yyyy or dd/mm/yyyy to dd-mm-yyyy and validates date.
     *
     * Throws $ER['012'] if $dt is not a valid date or not 0.
     * Returns valid and formatted date or 0.
     */
    public function filterDate($dt, $separator = "-")
    {
        global $objSystem, $ERR, $ER;

        if ($dt != 0) {
            $dt = preg_replace("([.]+)", $separator, $dt);
            $date = str_replace("/", $separator, $dt);
            if ($objSystem->settings['dates_format'] == 'USA') {
                list($m, $d, $y) = array_pad(explode($separator, $date, 3), 3, 0);
            } else {
                list($d, $m, $y) = array_pad(explode($separator, $date, 3), 3, 0);
            }
            if (ctype_digit("$m$d$y") && checkdate($m, $d, $y)) {
                return $date;
            }
            $ERR = $ER['012'];
        }
        return 0;
    }

    /**
     * Check if a string date is valid for insertion or update
     * to the database.
     *
     * @param string $datetime The given date.
     * @return bool Returns the validation result.
     *
     * @link http://stackoverflow.com/a/8105844/1718162 [SOURCE]
     */
    public function validateMysqlDatetime($datetime) {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        return ($dt) ? TRUE : FALSE;
    }
        
}
