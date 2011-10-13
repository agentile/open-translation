<?php
/**
 * 
 * OT Locale
 */
class OT_Locale
{
    /**
     * 
     * Locale entries. Keys and values
     * 
     * @var array
     * 
     */
    protected $trans = array();
    
    /**
     * 
     * The locale configuration settings
     * 
     * @var array
     * 
     */
    protected $default = array(
        'code' => 'en_US',
        'path' => null,
    );

    /**
     * __construct
     * Insert description here
     *
     *
     * @return
     *
     * @access
     * @static
     * @see
     * @since
     */
    public function __construct(array $default = array())
    {
        $this->default = array_merge_recursive($this->default, $default);
    }
    
    /**
     * 
     * Sets the locale code and clears out previous translations.
     * 
     * @param string $code A locale code, for example, 'en_US'.
     * 
     * @return void
     * 
     */
    public function setCode($code)
    {
        // set the code
        $this->default['code'] = $code;
        
        // reset the strings
        $this->trans = array();
    }
    
    /**
     * 
     * Returns the current locale code.
     * 
     * @return string The current locale code, for example, 'en_US'.
     * 
     */
    public function getCode()
    {
        return $this->default['code'];
    }
    
    /**
     * 
     * Sets the locale path
     * 
     * @param string $path A locale file path.
     * 
     * @return void
     * 
     */
    public function setPath($path)
    {
        // set the path
        $this->default['path'] = $path;
    }
    
    /**
     * 
     * Returns the current locale file path.
     * 
     * @return string The current locale file path.
     * 
     */
    public function getPath()
    {
        return $this->default['path'];
    }
    
    /**
     * 
     * Returns ISO 3166 country code for current locale code.
     * 
     * This is basically just the last two uppercase letters
     * from the locale code.
     * 
     * @return string
     * 
     */
    public function getCountryCode()
    {
        return substr($this->default['code'], -2);
    }
    
    /**
     * 
     * Returns RFC 1766 (XHTML) language code for current locale code.
     * 
     * This is the same as the locale code, but using a dash instead of an
     * underscore as a separator.
     * 
     * @return string
     * 
     */
    public function getLanguageCode()
    {
        return str_replace('_', '-', $this->default['code']);
    }
    
    /**
     * 
     * Returns an existing string from the translation array.
     * 
     * @param string $key The translation key.
     * 
     * @param array $replace An array of replacement values for the string.
     * 
     * @return string The translation string if it exists, or null if it
     * does not.
     * 
     */
    public function locale($key, $replace = null)
    {
        if (!isset($this->trans[$this->default['code']])) {
            $this->load($this->default['code']);
        }
        
        if (isset($this->trans[$this->default['code']][$key]) && !$replace) {
            return $this->trans[$this->default['code']][$key];
        } elseif (isset($this->trans[$this->default['code']][$key])) {
            return vsprintf($this->trans[$this->default['code']][$key], $replace);
        }
        
        return null;
    }
    
    /**
     * 
     * Loads the translation array by params or 
     * configuration set path and code
     * 
     * @return void
     * 
     */
    public function load($code = null, $path = null)
    {
        if (!$code) {
            $code = $this->default['code'];
        }
        
        if (!$path) {
            $path = $this->default['path'];
        }

        $file = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->default['code'] . '.php';

        // can we find the file?
        if (file_exists($file)) {
            // put the locale values into the shared locale array
            $this->trans[$code] = (array) include $file;
        } else {
            // could not find file.
            die("locale file: $file not found");
        }
    }
}
