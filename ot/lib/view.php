<?php
/**
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
    
    /**
     * Set some admin specific view/layout paths.
     */
    public function __construct()
    {
        $this->layout_path = dirname(dirname(__FILE__)) . '/admin/layout/';
        $this->view_path = dirname(dirname(__FILE__)) . '/admin/views/';
    }
    
    /**
     * Render view and layout. Set properties to be usable in views.
     */
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
    
    /**
     * Set locale object if it doesn't exist.
     */
    public function setLocale($config)
    {
        if (!$this->_locale) {
            $this->_locale = OT::getObject('OT_Locale', $config); 
        }
    }
    
    /**
     * Locale view helper method
     */
    public function locale($key, $replace = null)
    {
        if (!$this->_locale) {
            $locale_settings = OT::getConfigKey('native_locale');
            $this->setLocale($local_settings);
        }
        return $this->_locale->fetch($key, $replace);
    }
}
