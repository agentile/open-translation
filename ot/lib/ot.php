<?php 
/**
 * OT Arch Class
 */
class OT {
    
    // ot path
    public static $system;
    public $database;
    public $view;
    public $config = array();
    public $locale;
    
    public function __construct()
    {
        self::$system = dirname(dirname(__FILE__));
    }
    
    public function start()
    {
        if (!is_file(self::$system . '/lib/config.php')) {
            die('Please create a config.php file');
        }
        
        $this->config = include self::$system . '/lib/config.php';
        
        // ramp up some OT objects
        if (!isset($this->database)) {
            require self::$system . '/lib/database.php';
            $this->database = new OT_DB($this->config['database']);
        }
        
        if (!isset($this->view)) {
            require self::$system . '/lib/view.php';
            $this->view = new OT_View();
        }
    }
    
    public function getLocaleObject($config)
    {
        if (!$this->locale) {
            require self::$system . '/lib/locale.php';
            $this->locale = new OT_Locale($config);
        }

        return $this->locale;
    }
    
    public static function getIP()
    {
        $ip = '0.0.0.0';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_VIA'])) {
            $ip = $_SERVER['HTTP_VIA'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
