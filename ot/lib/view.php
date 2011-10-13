<?php
/**
 * 
 * OT View
 */
class OT_View
{
    public $layout = 'admin';
    public $layout_path;
    public $view_path;
    
    protected $_locale;
    
    protected $_escape = array(
        'quotes'  => ENT_COMPAT,
        'charset' => 'UTF-8',
    );
    
    public function __construct()
    {
        $this->layout_path = dirname(dirname(__FILE__)) . '/admin/layout/';
        $this->view_path = dirname(dirname(__FILE__)) . '/admin/views/';
    }
    
    public function render($vars, $view)
    {
        foreach ($vars as $key => $val) {
            $this->$key = $val;
        }
        
        header('Content-Type: text/html; charset: utf-8');

        // render view
        ob_start();
        require $this->view_path . $view . '.php';
        $view_content = ob_get_clean();
        
        // render layout
        ob_start();
        require $this->layout_path . $this->layout . '.php';
        echo ob_get_clean();
    }
    
    /**
     * 
     * Built-in helper for escaping output.
     * 
     * @param scalar $value The value to escape.
     * 
     * @return string The escaped value.
     * 
     */
    public function escape($value)
    {
        return htmlspecialchars(
            $value,
            $this->_escape['quotes'],
            $this->_escape['charset']
        );
    }
    
    public function locale($key, $replace)
    {
        if (!$this->_locale) {
            require OT::$system . '/lib/locale.php';
            $this->_locale = new OT_Locale();
        }
        return $this->_locale->locale($key, $replace);
    }
}