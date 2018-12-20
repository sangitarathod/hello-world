<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class WPAdm_Chats_Button {
    static public function nav_menu_link_attributes( $atts, $item, $args ) {
        if (preg_match("|wpadm-chat/$|Uis", $atts['href'])) {
            $atts['href'] = '#chat';
        }
        if (preg_match("|wpadm-chat-open/$|Uis", $atts['href'])) {
            $atts['href'] = '#chat_open';
        }
        if (preg_match("|wpadm-chat-close/$|Uis", $atts['href'])) {
            $atts['href'] = '#chat_close';
        }
        return $atts;
    }

    static public function my_nav_menu_profile_link($menu) {
        if (preg_match("|nav\-menus\.php$|Uis", $_SERVER['REQUEST_URI'])) {
//        'wpadm-chat', 'wpadm-chat-open', 'wpadm-chat-close';
            array_unshift(
                $menu,
                self::getWPADMChatPage('wpadm-chat'),
                self::getWPADMChatPage('wpadm-chat-open'),
                self::getWPADMChatPage('wpadm-chat-close')
            );
        }

        return $menu;
    }


    static public function getWPADMChatPage($name)
    {
        $get_posts = new WP_Query;
        $args = array(
//            'offset' => $offset,
            'pagename' => $name,
            'order' => 'ASC',
            'orderby' => 'title',
//            'posts_per_page' => $per_page,
//            'post_type' => $post_type_name,
            'suppress_filters' => true,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false
        );

        $pages = $get_posts->query($args);

        if (empty($pages)) {
            self::createWPAMDChatPage($name);
            $pages = $get_posts->query($args);
        }

        return $pages[0];
    }


    static public function createWPAMDChatPage($name) {
        $user_id = get_current_user_id();
        $page = array(
            'post_author' => $user_id,
            'post_content' => '',
            'post_content_filtered' => '',
            'post_title' => '#' . $name,
            'post_name' => $name,
            'post_excerpt' => '',
            'post_status' => 'private',
            'post_type' => 'page',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_password' => '',
            'to_ping' => '',
            'pinged' => '',
            'post_parent' => 0,
            'menu_order' => 0,
            'guid' => '',
            'import_id' => 0,
            'context' => '',
        );

        wp_insert_post($page);
    }


    public static function widgets_initial()
    {
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'widget.php';;
        register_widget('WPAdm_Chats_Widget');
    }



    public static function widget_scripts_load() {
        if (preg_match("|widgets\.php|Uis", $_SERVER['REQUEST_URI']) ) {

            wp_enqueue_style('minicolors', plugins_url('chats/assets/jquery.minicolors.css'));
            wp_enqueue_style('arcticmodal', plugins_url('chats/assets/jquery.arcticmodal.css'));
            wp_enqueue_style('minicolors');
            wp_enqueue_style('arcticmodal');

            wp_register_script( 'chats_minicolors', plugins_url('chats/assets/jquery.minicolors.min.js'), array('jquery'));
            wp_register_script( 'chats_arcticmodal', plugins_url('chats/assets/jquery.arcticmodal.min.js'), array('jquery'));
            wp_enqueue_script( 'chats_minicolors' );
            wp_enqueue_script( 'chats_arcticmodal' );
            wp_enqueue_script( 'jquery-ui-slider' );
        }
    }



}