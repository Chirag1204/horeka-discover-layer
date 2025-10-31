<?php
/**
 * Plugin Name: Horeka Discover & Quick
 * Plugin URI: https://horeka.app
 * Description: Complete homepage and discovery layer for Horeka marketplace - connecting local resellers and customers
 * Version: 1.0.0
 * Author: Horeka Team
 * Author URI: https://horeka.app
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: horeka-discover-quick
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.1
 */

if (!defined('ABSPATH')) {
    exit;
}

define('HOREKA_VERSION', '1.0.0');
define('HOREKA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HOREKA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('HOREKA_PLUGIN_FILE', __FILE__);

// Load required files
require_once HOREKA_PLUGIN_DIR . 'includes/helpers.php';
require_once HOREKA_PLUGIN_DIR . 'includes/class-horeka-security.php';
require_once HOREKA_PLUGIN_DIR . 'includes/class-horeka-admin.php';
require_once HOREKA_PLUGIN_DIR . 'includes/class-horeka-rest.php';
require_once HOREKA_PLUGIN_DIR . 'includes/class-horeka-shortcodes.php';

class Horeka_Discover_Quick {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
        
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('init', [$this, 'load_textdomain']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        
        // Initialize classes
        new Horeka_Admin();
        new Horeka_REST();
        new Horeka_Shortcodes();
    }
    
    public function activate() {
        $this->register_post_types();
        $this->register_taxonomies();
        flush_rewrite_rules();
        
        // Set default options
        if (!get_option('horeka_settings')) {
            $defaults = [
                'welcome_title' => __('Welcome to Horeka', 'horeka-discover-quick'),
                'discover_headline' => __('Discover Local Products', 'horeka-discover-quick'),
                'discover_description' => __('Find home goods from nearby resellers', 'horeka-discover-quick'),
                'quick_headline' => __('Quick Order', 'horeka-discover-quick'),
                'quick_description' => __('Fast track your order directly', 'horeka-discover-quick'),
                'quick_target_url' => home_url('/quick-order'),
                'featured_products_limit' => 6,
                'show_resellers' => true,
                'show_brands' => true,
            ];
            update_option('horeka_settings', $defaults);
        }
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    public function register_post_types() {
        // Reseller CPT
        register_post_type('horeka_reseller', [
            'labels' => [
                'name' => __('Resellers', 'horeka-discover-quick'),
                'singular_name' => __('Reseller', 'horeka-discover-quick'),
                'add_new' => __('Add New Reseller', 'horeka-discover-quick'),
                'add_new_item' => __('Add New Reseller', 'horeka-discover-quick'),
                'edit_item' => __('Edit Reseller', 'horeka-discover-quick'),
                'view_item' => __('View Reseller', 'horeka-discover-quick'),
            ],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'menu_icon' => 'dashicons-store',
            'capability_type' => 'post',
            'rewrite' => ['slug' => 'resellers'],
        ]);
        
        // Reservation CPT
        register_post_type('horeka_reservation', [
            'labels' => [
                'name' => __('Reservations', 'horeka-discover-quick'),
                'singular_name' => __('Reservation', 'horeka-discover-quick'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'horeka-dashboard',
            'show_in_rest' => true,
            'supports' => ['title'],
            'capability_type' => 'post',
            'capabilities' => [
                'create_posts' => 'do_not_allow',
            ],
            'map_meta_cap' => true,
        ]);
    }
    
    public function register_taxonomies() {
        register_taxonomy('horeka_brand', ['product', 'horeka_reseller'], [
            'labels' => [
                'name' => __('Brands', 'horeka-discover-quick'),
                'singular_name' => __('Brand', 'horeka-discover-quick'),
            ],
            'public' => true,
            'hierarchical' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'brands'],
        ]);
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('horeka-discover-quick', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function register_rest_routes() {
        // Routes registered in Horeka_REST class
    }
    
    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'horeka-frontend',
            HOREKA_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            HOREKA_VERSION
        );
        
        wp_enqueue_script(
            'horeka-frontend',
            HOREKA_PLUGIN_URL . 'assets/js/frontend.js',
            ['jquery'],
            HOREKA_VERSION,
            true
        );
        
        wp_localize_script('horeka-frontend', 'horekaData', [
            'restUrl' => rest_url('horeka/v1/'),
            'nonce' => wp_create_nonce('wp_rest'),
            'strings' => [
                'enterPhone' => __('Please enter your phone number:', 'horeka-discover-quick'),
                'invalidPhone' => __('Please enter a valid 10-digit phone number', 'horeka-discover-quick'),
                'success' => __('Reservation created successfully!', 'horeka-discover-quick'),
                'error' => __('Failed to create reservation. Please try again.', 'horeka-discover-quick'),
            ]
        ]);
    }
    
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'horeka') === false) {
            return;
        }
        
        wp_enqueue_media();
        
        wp_enqueue_style(
            'horeka-admin',
            HOREKA_PLUGIN_URL . 'assets/css/admin.css',
            [],
            HOREKA_VERSION
        );
        
        wp_enqueue_script(
            'horeka-admin',
            HOREKA_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery'],
            HOREKA_VERSION,
            true
        );
    }
}

// Initialize plugin
function horeka_discover_quick() {
    return Horeka_Discover_Quick::get_instance();
}

horeka_discover_quick();