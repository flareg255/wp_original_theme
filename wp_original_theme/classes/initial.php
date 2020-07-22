<?php

require_once( dirname(__FILE__) . '/post_type/post_type.php' );

class Initial {

    public function init_func() {

        add_theme_support( 'menus' );
        add_action( 'wp_enqueue_scripts', array( $this, 'init_rm' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enq_front' ) );
        add_action( 'admin_menu', array( $this, 'rm_menu' ) );

        $this->init_post_type();
    }

    public function init_rm() {

        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action( 'wp_head', 'feed_links_extra', 3 );
        remove_action( 'wp_head', 'feed_links', 2 );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'index_rel_link' );
        remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
        remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
        remove_action( 'wp_head', 'wp_generator' );
        remove_filter('the_content', 'wpautop');
        remove_filter('the_excerpt', 'wpautop');

    }

    public function enq_front() {

        wp_deregister_script('jquery');

        wp_enqueue_style( 'boot-strap-css', get_template_directory_uri() . '/css/bootstrap.min.css', array(), null, false );
        wp_enqueue_style( 'style', get_stylesheet_uri() );
        wp_enqueue_script( 'jquery', get_template_directory_uri() . '/js/jquery.min.js', array(), null, false );
        wp_enqueue_script( 'boot-strap-js', get_template_directory_uri() . '/js/bootstrap.bundle.min.js', array('jquery'), null, false );

    }

    public function rm_menu (){
        remove_menu_page( 'edit.php' );
    }

    public function init_post_type() {
        $post_type = new Post_type();
        $post_type->init();
    }
}

?>