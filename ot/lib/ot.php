<?php 
/**
 * OT Arch Class
 */
class OT {
    
    // ot base path
    public static $system;
    public static $config = array();
    public static $_object_store = array();
    
    /**
     * Fetch object from object store.
     */
    public static function getObject($class_name, $args = array())
    {
        if (!isset(self::$_object_store[$class_name])) {
            self::$system = dirname(dirname(__FILE__));
            $file = strtolower(str_replace('OT_', '', $class_name));
            require self::$system . '/lib/' . $file . '.php';
            self::$_object_store[$class_name] = new $class_name($args);
        }
        
        return self::$_object_store[$class_name];
    }
    
    /**
     * Get config value by key, allows for returnable value if config key 
     * is not set
     */
    public static function getConfigKey($key, $ret = null)
    {
        if (!self::$config) {
            self::$system = dirname(dirname(__FILE__));
            self::loadConfig();
        }
        
        if (isset(self::$config[$key])) {
            return self::$config[$key];
        }
        return $ret;
    }
    
    /**
     * Load config 
     */
    public static function loadConfig()
    {
        if (!is_file(self::$system . '/lib/config.php')) {
            die('Please create a config.php file');
        }
        
        self::$config = include self::$system . '/lib/config.php';
    }

    /**
     * Get IP Address of a user
     */
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
