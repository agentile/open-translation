<?php # vim:ts=4:sw=4:et:
/**
 * AJAX Dispatcher
 */
require_once 'ot.php';
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
    protected $db = null;

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

        $db_config = OT::getConfigKey('database');
        $this->db = OT::getObject('OT_DB', $db_config); 

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

        header('Content-Type: application/json');
        echo json_encode($ret);
    }

    public function ajax_fetch_page_translations()
    {
        $page = $this->_get['page'];
        $code = $this->_get['native_code'];
        $text = $this->_get['selected'];
        $this->data = $this->db->fetchPageTranslation($page, $code, $text);
        $this->success = true;
    }

    public function ajax_fetch_available_locales()
    {
        $this->data = OT::getConfigKey('available_locales', array());
        $this->success = true;
    }
    
    public function ajax_create_translation_entry()
    {
        $page = $this->_post['page'];
        $native_code = $this->_post['native_code'];
        $native_text = $this->_post['native_text'];
        $translated_code = $this->_post['translated_code'];
        $translated_text = $this->_post['translated_text'];
        $ip = OT::getIP();
        $this->success = $this->db->insertEntry($page, $native_code, $native_text, $translated_code, $translated_text, $ip);
    }
    
    public function ajax_vote_up()
    {
        $this->success = $this->db->voteUpById($this->_post['tid'], OT::getIP());
    }
    
    public function ajax_vote_down()
    {
        $this->success = $this->db->voteDownById($this->_post['tid'], OT::getIP());
    }
}

$ajax = new OT_Ajax();
