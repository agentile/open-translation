<?php # vim:ts=4:sw=4:et:
/**
 * AJAX Dispatcher
 */
require_once 'database.php';
class OT_Ajax {

    // Ajax response properties
    public $success = false;
    public $error = null;
    public $message = null;
    public $data = null;

    // POST and GET data
    protected $_post = array();
    protected $_get  = array();

    // Database
    protected $_db = null;

    /**
     * _construct
     * Ajax dispatcher
     *
     * @param $param
     *
     * @return void
     */
    public function __construct()
    {
        $this->_post = $_POST;
        $this->_get  = $_GET;

        $this->_db = new OT_DB();

        // look for ajax_action to properly dispatch
        if (isset($this->_post['ajax_action'])) {
            $action = $this->_post['ajax_action'];
        } elseif (isset($this->_get['ajax_action'])) {
            $action = $this->_get['ajax_action'];
        } else {
            $action = false;
        }

        if ($action === false) {
            $this->error = 'No ajax_action data found.';
            $this->success = false;
            $this->message = 'An AJAX action was not found in your request.';
        } else {
            $method = 'ajax_' . $action;
            if (method_exists($this, $method)) {
                try {
                    $this->$method();
                } catch (Exception $e) {
                    $this->error = $e->getMessage();
                }
            } else {
                $this->error = 'Invalid AJAX Action.';
                $this->success = false;
                $this->message = 'The AJAX Action specified could not be found.';
            }
        }

        $this->_returnResponse();
    }

    /**
     * _returnResponse
     * JSON encodes properties set by AJAX functions
     *
     * @return void
     */
    protected function _returnResponse()
    {
        $ret = array(
            'success' => $this->success,
            'error'   => $this->error,
            'message' => $this->message,
            'data'    => $this->data
        );

        echo json_encode($ret);
    }

    public function ajax_fetch_page_translations()
    {
        $page = $this->_get['page'];
        $code = $this->_get['native_code'];
        $text = $this->_get['selected'];
        $this->data = $this->_db->fetchPageTranslation($page, $code, $text);
        $this->success = true;
    }

    public function ajax_fetch_available_locales()
    {
        $config = include 'config.php';
        $locales = $config['translations']['available_locales'];
        $this->data = $locales;
        $this->success = true;
    }
}

$ajax = new OT_Ajax();
