<?php

namespace Application\Site\Model;

use System\Entity;

/**
 * Define a Entity Settings.
 * 
 * @author Jonatan Noronha Reginato
 */
class Settings extends Entity
{
    private $settings;
    private static $instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
    }

    private function loadSettings()
    {
        $settings_obj = new Settings;
        $settings_model = new SettingsModel();

        $query = $settings_model->get($settings_obj);

        $this->settings = array(
            'system_setting' => $settings_obj,
            'return' => $query
        );

        // Verifica se a url necessita https
        //if ($this->getSettings('https') == 'y') {
        //    $this->setSettings('site_url',
        //        !empty($this->getSettings('https_url'))
        //        ? $this->getSettings('https_url')
        //        : str_replace('http://', 'https://', $this->getSettings('site_url'))
        //    );
        //}
    }






















    /**
     * filter
     * Faz a varredura no filtro de palavras para verificar
     * se o usuário inseriu alguma palavra não permitida
     *
     * @global object $objDb
     * @param string $txt
     * @return boolean
     */
    function filter($txt)
    {
        global $objDb;

        $query = "  SELECT * FROM " . DB_PREFIX . "filter_words";
        $objDb->directQuery($query);

        $initial_word = $txt;
        $final_word = $txt;
        while ($word = $objDb->fetch()) {
            $final_word = preg_replace('(' . $word['word'] . ')', '', $final_word);
        }
        if ($initial_word != $final_word){
            return true;
        }
        return false;
    }


    /**
     * moveFile
     *
     */
    function moveFile($from, $to, $removeorg = true)
    {
        $upload_mode = (@ini_get('open_basedir') || @ini_get('safe_mode') || strtolower(@ini_get('safe_mode')) == 'on') ? 'move' : 'copy';

        if (!is_file($from)) {
            return false;
        }

        switch ($upload_mode) {
            case 'copy':
                if (@copy($from, $to)) {
                    if (!@move_uploaded_file($from, $to)) {
                        return false;
                    }
                }
                if ($removeorg)
                    @unlink($from);
                break;

            case 'move':
                if (!@move_uploaded_file($from, $to)) {
                    if (!@copy($from, $to)) {
                        return false;
                    }
                }
                if ($removeorg)
                    @unlink($from);
                break;
        }
        @chmod($to, 0666);
        return true;
    }







































    /*
      Accepts either simple or array input
      simple:
      writeSetting('setting_name', 'setting_value', 'string');
      array:
      writeSetting(array(
          array('some_setting_name', 'some_setting_value', 'string'),
          array('another_setting_name', 'another_setting_value', 'string')
      ));
     */

    function writeSetting($settings, $value = '', $type = 'string')
    {
        global $objDb;

        $modified_by = $_SESSION['WEBID_ADMIN_IN'];
        $modified_date = $this->ctime;

        if (is_string($settings)) {
            $settings = array(array($settings, $value, $type));
        }

        foreach ($settings as $setting) {
            // Check arguments are set
            if (!isset($setting[0]) || !isset($setting[1])) {
                continue;
            }
            $setting[2] = (isset($setting[2])) ? $setting[2] : 'string';

            $field_name = $setting[0];
            $value = $setting[1];
            $type = $setting[2];

            // TODO: Use the data type to check if the value is valid
            switch ($type) {
                case "string":
                case "str":
                    break;
                case "integer":
                case "int":
                    $value = intval($value);
                    break;
                case "boolean":
                case "bool":
                    $value = ($value) ? 1 : 0;
                    break;
                case "array":
                    $value = serialize($value);
                    break;
                default:
                    break;
            }

            $query = "  SELECT
                            *
                        FROM
                            " . DB_PREFIX . "settings
                        WHERE
                            fieldname = :fieldname";

            $params[] = array(':fieldname', $field_name, 'str');
            $objDb->query($query, $params);

            if ($objDb->numRows() > 0) {
                $type = $objDb->result('fieldtype');
                $query = "  UPDATE
                                " . DB_PREFIX . "settings
                            SET
                                fieldtype = :fieldtype,
				value = :value,
				modifieddate = :modifieddate,
				modifiedby = :modifiedby
                            WHERE
                                fieldname = :fieldname";
            } else {
                $query = "  INSERT INTO " . DB_PREFIX . "settings
                                (fieldname, fieldtype, value, modifieddate, modifiedby)
                            VALUES
                                (:fieldname, :fieldtype, :value, :modifieddate, :modifiedby)";
            }
            $params[] = array(':fieldname', $field_name, 'str');
            $params[] = array(':fieldtype', $type, 'str');
            $params[] = array(':value', $value, 'str');
            $params[] = array(':modifieddate', $modified_date, 'int');
            $params[] = array(':modifiedby', $modified_by, 'int');
            $objDb->query($query, $params);
            $this->settings[$field_name] = $value;
        }
    }

    public function loadAuctionTypes()
    {
        global $objDb;
        global $MSG;

        $query = "  SELECT
                        id,
                        language_string
                    FROM
                        " . DB_PREFIX . "auction_types";

        $objDb->directQuery($query);
        $this->settings['auction_types'] = [];
        while ($row = $objDb->fetch()) {
            $this->settings['auction_types'][$row['id']] = $MSG[$row['language_string']];
        }
    }


    function checkDenyIp()
    {
        global $objDb;
        global $MSG;

        if (!defined('ERROR_PAGE') && !defined('IN_ADMIN')) {

            $query = "  SELECT id
                        FROM " . DB_PREFIX . "usersips
                        WHERE ip = :user_ip AND action = 'deny'";

            $params[] = array(':user_ip', USER_IP, 'str');
            $objDb->query($query, $params);

            if ($objDb->numRows() > 0) {
                $_SESSION['msg_title'] = $MSG['2_0027'];
                $_SESSION['msg_body'] = $MSG['2_0026'];
                header('location: message.php');
                exit;
            }
        }
    }

    function checkMaintainanceMode()
    {
        global $user;

        if ($this->settings['maintainance_mode_active']) {
            if ($objUser->logged_in && ($objUser->user_data['nick'] == $this->settings['superuser'] || $objUser->user_data['id'] == $this->settings['superuser'])) {
                return false;
            }
            return true;
        }
        return false;
    }

}
