<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class WPAdm_Chats_Widget extends WP_Widget {

    /**
     * create default params for form
     *
     */
    public function __construct()
    {
        $widget_ops = array( 'classname' => 'WPAdm_Chats_Widget', 'description' => 'Chats Control' );

        $control_ops = array(
//            'widths' => 400, 'heights' => 550,
            'id_base' => 'wpadm_chats_widget' );
        if (version_compare(phpversion(), '5.0.0', '>=')) {
            parent::__construct('wpadm_chats_widget', 'WPAdm Chats Control', $widget_ops, $control_ops);
        } else {
            $this->WP_Widget('wpadm_chats_widget', 'WPAdm Chats Control', $widget_ops, $control_ops );
        }
    }

    /**
     *  this is method shows the counter in sidebar
     *
     * @param array $args - default params for shows widget
     * @param array $instance - widget params
     */
    public function widget( $args, $instance )
    {
        echo $args['before_widget'];

        self::draw(self::checkInstance($instance));

        echo $args['after_widget'];
    }


    static function draw($instance) {
        $hover = $instance['hover'];

        if (isset($instance['type'])) {
            $btn_id = uniqid('wpadm_chats_widget_');
            $btn = '';
            $css = array();
            $css_hover = array();

            $class = ($instance['classname']) ? "class='{$instance['classname']}'" : '';

            if ($instance['color']) { $css[] = "color: {$instance['color']}"; }

            if ($hover && $instance['hover_color']) { $css_hover[] = "color: {$instance['hover_color']}"; }


            if ($instance['type'] == 'button') {

                $btn = "<button id='{$btn_id}' type='button' onclick='wpadm_chat();' {$class}>{$instance['text']}</button>";

                if ($instance['background']) { $css[] = "background-color: {$instance['background']}"; }
                if ($hover && $instance['background']) { $css_hover[] = "background-color: {$instance['hover_background']}"; }

                if ($instance['bordercolor']) { $css[] = "border: 1px solid {$instance['bordercolor']}"; }
                if ($hover && $instance['bordercolor']) { $css_hover[] = "border: 1px solid {$instance['hover_bordercolor']}"; }

                if ($instance['border_radius']) { $css[] = "border-radius: {$instance['border_radius']}px"; }

                $shadow_size = $instance['shadow_size'];
                $shadow_color = $instance['shadow_color'];
                if ($shadow_size && $shadow_color) {
                    $css[]= "box-shadow: {$shadow_size}px {$shadow_size}px 10px 0px {$shadow_color}";
                }

                $hover_shadow_color = $instance['hover_shadow_color'];
                if ($hover && $shadow_size && $hover_shadow_color) {
                    $css_hover[]= "box-shadow: {$shadow_size}px {$shadow_size}px 10px 0px {$hover_shadow_color}";
                }


            } else if ($instance['type'] == 'link') {
                $btn ="<a  id='{$btn_id}' href='#chat' {$class}>{$instance['text']}</a>";
            }

            echo "<style type='text/css'>
                    #{$btn_id} {".implode(';', $css)." }
                    #{$btn_id}:hover {".implode(';', $css_hover)." }

                  </style>";
            echo $btn;
        }

    }


    /**
     * @param $instance
     * @return array
     */
    static public function checkInstance($instance) {
        if (!isset($instance['text'])) { $instance['text'] = __('Chat', 'Chats'); }
        if (!isset($instance['type'])) { $instance['type'] = 'button'; }
        if (!isset($instance['classname'])) { $instance['classname'] = ''; }
        if (!isset($instance['background'])) { $instance['background'] = ''; }
        if (!isset($instance['color'])) { $instance['color'] = ''; }
        if (!isset($instance['bordercolor'])) { $instance['bordercolor'] = ''; }
        if (!isset($instance['border_radius'])) { $instance['border_radius'] = 0; }
        if (!isset($instance['shadow_size'])) { $instance['shadow_size'] = 0; }
        if (!isset($instance['shadow_color'])) { $instance['shadow_color'] = ''; }

        if (!isset($instance['hover'])) { $instance['hover'] = ''; }
        if (!isset($instance['hover_background'])) { $instance['hover_background'] = ''; }
        if (!isset($instance['hover_bordercolor'])) { $instance['hover_bordercolor'] = ''; }
        if (!isset($instance['hover_shadow_color'])) { $instance['hover_shadow_color'] = ''; }
        if (!isset($instance['hover_color'])) { $instance['hover_color'] = ''; }
        return $instance;
    }
    /**
     * shows form in widget page
     *
     * @param array $instance - widget params
     */
    public function form( $instance )
    {
        $instance = $this->checkInstance($instance);
        require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'widget_form.php';
    }

    /**
     * Update option for plugin
     *
     * @param array $new_instance
     * @param array $old_instance
     */
    public function update( $new_instance, $old_instance ) {

        return WPAdm_Chats_Widget::normalizeInstance($new_instance);

    }

    static function normalizeInstance($new_intance) {
        return $new_intance;


        $instance = array();
        $instance[ 'text' ] = strip_tags( $new_instance[ 'text' ] );
        $instance[ 'type' ] = htmlentities( $new_instance[ 'type' ] );
        $instance[ 'color' ] = htmlentities( $new_instance[ 'color' ] );
        $instance[ 'background' ] = htmlentities( $new_instance[ 'background' ] );
        $instance[ 'bordercolor' ] = htmlentities( $new_instance[ 'bordercolor' ] );
        $instance[ 'classname' ] = htmlentities( $new_instance[ 'classname' ] );
        return $instance;

    }


    /********************************/




}