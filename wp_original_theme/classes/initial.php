<?php

require_once( dirname(__FILE__) . '/post_type/post_type.php' );

class Initial {

    public function init_func() {

        add_theme_support( 'menus' );
        add_action( 'wp_enqueue_scripts', array( $this, 'init_rm' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enq_front' ) );
        add_action( 'admin_menu', array( $this, 'rm_menu' ) );

        add_theme_support( 'post-thumbnails' );
        add_image_size( 'org_thumb100', 100, 100, true );
        add_image_size( 'org_thumb150', 150, 150, true );
        add_filter( 'post_thumbnail_html', array( $this, 'thumbnail_wh_delete' ) );

        add_action( 'after_setup_theme', array( $this, 'setup_logo' ) );
        add_filter( 'get_custom_logo', array( $this, 'add_custom_logo_url' ) );

        add_filter('admin_footer_text', '__return_empty_string');
        add_action( 'admin_menu', array( $this, 'remove_footer_version' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_style_script' ) );

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

    public function thumbnail_wh_delete( $html ){
        $html = preg_replace('/(width|height)="\d*"\s/', '', $html);
        return $html;
    }

    public function remove_footer_version() {
        remove_filter('update_footer', 'core_update_footer');
    }

    public function setup_logo() {
        add_theme_support('custom-logo');
    }

    public function add_custom_logo_url() {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $html = sprintf( '<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url">%2$s</a>',
                    esc_url( home_url() ),
                    wp_get_attachment_image( $custom_logo_id, 'full', false, array(
                        'class'    => 'w-100',
                    ) )
                );
        $html = preg_replace('/(width|height)="\d*"\s/', '', $html);
        return $html;
    }

    public function enqueue_admin_style_script() {
        wp_enqueue_style( 'admin-style', get_template_directory_uri() . '/admin/style.css' );
        wp_enqueue_script( 'admin-script', get_template_directory_uri() . '/admin/script.js', array(), '1.0.0', true );
    }

    public function init_post_type() {
        $post_type = new Post_type();
        $post_type->init();
    }
}

?>