<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'widget.php';

class WPAdm_Chats_Shortcode {

    public $id;
    public $code;
    public $instance;

    public function __construct($id) {
        $this->id = $id;
        $this->code = 'wpadm_chats_button_' . $id;
        $this->load();
    }


    public function shortcode__wpadm_chat() {
        WPAdm_Chats_Widget::draw($this->instance);
    }

    public function load() {
        $this->instance = get_option($this->code, array());
        $this->checkInstance();
    }

    public function form()
    {
        $instance = $this->instance;
        require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'widget_form.php';
    }

    public function get_field_id( $field_name ) {
        return 'wpadm_shotcode_' . $this->id.'_' . $field_name;
    }

    public function get_field_name($field_name) {
        return 'wpadm_shotcode[' . $this->id . '][' . $field_name . ']';
    }

    /**
     * @param $instance
     * @return array
     */
    public function checkInstance() {
        $this->instance = WPAdm_Chats_Widget::checkInstance($this->instance);
    }

    public function loadFromArray($instance) {
        $this->instance = WPAdm_Chats_Widget::normalizeInstance($instance);
    }


    public function save() {
        update_option($this->code, $this->instance);
    }

}