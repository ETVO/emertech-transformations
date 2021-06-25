<?php
/**
 * Plugin Name: Emertech Transformations
 * Description: Transformações da Emertech
 * Author: Estevão Rolim
 * Author URI: https://www.linkedin.com/in/estevaoprolim/
 * Version: 1.0
 * 
 * @package Emertech Transform Plugin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Core constants 
define("EMERTECH_TRANSFORM_DIR", plugin_dir_path(__DIR__) . 'emertech-transform/');
define("EMERTECH_TRANSFORM_URL", plugins_url("emertech-transform/"));
define("EMERTECH_TRANSFORM_CLASS_NAME", "Emertech_Transform_Plugin");

/**
 * Emertech transformations plugin class
 */
final class Emertech_Transform_Plugin {

    /**
     * Add actions, filters and call functions
     * 
     * @since 1.0
     */
    public function __construct() {

        // Setup plugin constants
        $this->plugin_constants();

        // Initialize blocks PHP
        add_action('init', array(EMERTECH_TRANSFORM_CLASS_NAME, 'plugin_setup'));

        // Enqueue scripts      
        add_action('wp_enqueue_scripts', array(EMERTECH_TRANSFORM_CLASS_NAME, 'plugin_css'));
        add_action('wp_enqueue_scripts', array(EMERTECH_TRANSFORM_CLASS_NAME, 'plugin_js'));

        add_action('admin_enqueue_scripts', array(EMERTECH_TRANSFORM_CLASS_NAME, 'plugin_admin_css'));
        add_action('admin_enqueue_scripts', array(EMERTECH_TRANSFORM_CLASS_NAME, 'plugin_admin_js'));
        
        // Include template file for transform single page
        add_filter( 'template_include', [$this, 'transform_page_template'], 99 );
    }

    /**
     * Define plugin core constants
     *
     * @since 1.0
     */
    public static function plugin_constants() {

        // JS and CSS paths
        define('EMERTECH_TRANSFORM_JS_URL', EMERTECH_TRANSFORM_URL . 'assets/js/');
        define('EMERTECH_TRANSFORM_CSS_URL', EMERTECH_TRANSFORM_URL . 'assets/css/');

        // Include paths
        define('EMERTECH_TRANSFORM_INC_DIR', EMERTECH_TRANSFORM_DIR . 'inc/');
        define('EMERTECH_TRANSFORM_INC_URL', EMERTECH_TRANSFORM_URL . 'inc/');
    } 

    /**
     * Setup plugin files
     *
     * @since 1.0
     */
    public static function plugin_setup() {

		$dir = EMERTECH_TRANSFORM_INC_DIR;

		require_once $dir . 'transform/transform-cpt.php';
		require_once $dir . 'helpers.php';
    }

    /**
     * Enqueue plugin CSS for admin
     *
     * @since 1.0
     */
    public static function plugin_admin_css() {

        $dir = EMERTECH_TRANSFORM_CSS_URL;

        wp_enqueue_style(
            'emertech-transform', 
            $dir . 'admin.css',
            null,
            null
        );

    }

    /**
     * Enqueue plugin JS for admin
     *
     * @since 1.0
     */
    public static function plugin_admin_js() {

        $dir = EMERTECH_TRANSFORM_JS_URL;

    }

    /**
     * Enqueue plugin CSS
     *
     * @since 1.0
     */
    public static function plugin_css() {

        $dir = EMERTECH_TRANSFORM_CSS_URL;

        wp_enqueue_style(
            'emertech-transform', 
            $dir . 'app.css',
            null,
            null
        );

    }

    /**
     * Enqueue plugin JS
     *
     * @since 1.0
     */
    public static function plugin_js() {

        $dir = EMERTECH_TRANSFORM_JS_URL;

        wp_enqueue_script(
            'emertech-transform-scripts',
            $dir . 'app.js',
            null,
            null,
            true
        );

    }
    
    function transform_page_template( $template ) {
        $file_name = 'single-transform.php';
        
        // If the page is a singular of transform custom post
        if ( is_singular( 'transform' )  ) {
            
            // If template is not found in theme's folder, use plugin's template as a fallback
            if ( locate_template( $file_name ) ) {
                $new_template = locate_template( $file_name );
            } else {
                $new_template = dirname( __FILE__ ) . '/' . $file_name;
            }

            if ( '' != $new_template ) {
                return $new_template ;
            }
        }
        return $template;
    }
}

new Emertech_Transform_Plugin();