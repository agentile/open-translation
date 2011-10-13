<?php 
/**
 * OT Arch Class
 */
class OT {
    
    // ot path
    public static $system;
    public $database;
    public $view;
    
    public function __construct()
    {
        self::$system = dirname(dirname(__FILE__));
    }
    
    public function start()
    {
        // ramp up some OT objects
        if (!isset($this->database)) {
            require self::$system . '/lib/database.php';
            $this->database = new OT_DB();
        }
        
        if (!isset($this->view)) {
            require self::$system . '/lib/view.php';
            $this->view = new OT_View();
        }
    }
}
