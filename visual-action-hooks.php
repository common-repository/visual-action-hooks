<?php
/**
 * Plugin Name: Visual Action Hooks
 * Description: Detect & display actions added with "do_action" from any theme or plugin
 * Version: 1.0.0
 * Author: codinghabits
 * Requires at least: 5.0
 * Author URI: https://coding-habits.com
 * Text Domain: visual-action-hooks
 * Domain Path: /languages/
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Visual_Action_Hooks' ) ) {
    define( 'CVAH_TEXTDOMAIN', 'visual-action-hooks' );
    define( 'CVAH_PREFIX', 'cvah' );
    define( 'CVAH_URL', plugin_dir_url( __FILE__ ) );
    class Visual_Action_Hooks {

        // Instance of this class.
        protected static $instance = null;

        public function __construct() {

            // Load translation files
            // add_action( 'init', array( $this, 'add_translation_files' ) );

            // Admin page
            // add_action('admin_menu', array( $this, 'setup_menu' ));


            // Add settings link to plugins page
            // add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), array( $this, 'add_settings_link' ) );

            // Register plugin settings fields
            // register_setting( CVAH_PREFIX . '_settings', CVAH_PREFIX . '_email_message', array('sanitize_callback' => array( 'Visual_Action_Hooks', 'sanitize_code' ) ) );

            add_action('admin_bar_menu', array( $this, 'toolbar_link' ), 9999999);
            add_action( 'wp_head', array( $this, 'highlight_hooks' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts_and_styles' ) );
        }

        public function add_scripts_and_styles () {
            if ( current_user_can( 'administrator' ) ) {
                wp_enqueue_script( CVAH_PREFIX . '-scripts', CVAH_URL . 'assets/js/script.js', ['jquery'] );
                wp_enqueue_style( CVAH_PREFIX . '-styles', CVAH_URL . 'assets/css/style.css' );
            }
        }

        public function highlight_hooks () {
            if ( current_user_can( 'administrator' ) ) {
                $hooks = get_transient( 'chilla_detected_action_hooks' );
                if ( empty( $hooks ) ) {
                    $hooks = $this->get_action_hooks();
                    $hooks = filter_var_array( $hooks, FILTER_SANITIZE_STRING, false );
                    set_transient( 'chilla_detected_action_hooks', $hooks, 60 * 60 * 24 );
                }
                foreach ( $hooks as $hook ) {
                    add_action( $hook, function () use ($hook) {
                        if ( is_admin() ) {
                            return;
                        }
                        echo '<div class="chilla-visual-hook" data-hook="' . esc_attr( $hook ) . '"><span class="chilla-visual-hook-name">' . esc_html( $hook ) . '</span>';
                    }, -99999999999 );
                    add_action( $hook, function () {
                        if ( is_admin() ) {
                            return;
                        }
                        echo '</div>';
                    }, 99999999999 );
                }
            }
        }

        public function get_action_hooks () {
            $theme_hooks = $this->get_hooks_from_dir( ABSPATH . '/wp-content/themes' );
            $plugins_hooks = $this->get_hooks_from_dir( ABSPATH . '/wp-content/plugins' );
            $all_hooks = array_merge( $theme_hooks, $plugins_hooks );
            return array_unique( $all_hooks );
        }

        public function get_hooks_from_dir ( $dir ) {
            $php_files = $this->recursive_search( $dir, "/^.*\.php$/" );
            $all_hooks = Array();
            foreach ($php_files as $file) {
                $regexp = '/do_action\(\s*(?:\'|\")(.*?)(?:\'|\").*\)/';
                preg_match_all($regexp, file_get_contents($file), $keys, PREG_PATTERN_ORDER);
                $all_hooks = array_merge($all_hooks, $keys[1]);
            }
            $all_hooks = array_unique($all_hooks);
            // remove dynamic hooks with variables {$var}
            foreach ( $all_hooks as $key => $action ) {
                if ( strpos( $action, '$' ) !== false ) {
                    unset( $all_hooks[ $key ] );
                }
            }
            return $all_hooks;
        }

        private function recursive_search($folder, $pattern) {
            $dir = new RecursiveDirectoryIterator($folder);
            $ite = new RecursiveIteratorIterator($dir);
            $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
            $fileList = array();
            foreach($files as $file) {
                $fileList[] = $file[0];
            }
            return $fileList;
        }

        public function toolbar_link( $wp_admin_bar ) {
            $args = array(
                'id' => 'chinchillabrains-visual-hooks',
                'title' => 'Visual Action Hooks', 
                'href' => '#', 
                'meta' => array(
                    'class' => 'chvh', 
                    'title' => 'Visual Action Hooks'
                    )
            );
            $wp_admin_bar->add_node($args);
            
            $args = array(
                'id' => 'chinchillabrains-visual-hooks-toggle',
                'title' => 'Toggle visibility', 
                'href' => '#',
                'parent' => 'chinchillabrains-visual-hooks', 
                'meta' => array(
                    'class' => 'chvh-toggle', 
                    'title' => 'Toggle action hooks'
                    )
            );
            $wp_admin_bar->add_node($args);
        
        }

        // public static function sanitize_code( $input ) {        
        //     $sanitized = wp_kses_post( $input );
        //     if ( isset( $sanitized ) ) {
        //         return $sanitized;
        //     }
            
        //     return '';
        // }

        // public function add_translation_files () {
        //     load_plugin_textdomain( CVAH_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        // }

        // public function setup_menu() {
        //     add_management_page(
        //         __( 'Plugin Settings Title here...', CVAH_TEXTDOMAIN ),
        //         __( 'Plugin Settings Title here...', CVAH_TEXTDOMAIN ),
        //         'manage_options',
        //         CVAH_PREFIX . '_settings_page',
        //         array( $this, 'admin_panel_page' )
        //     );
        // }

        // public function admin_panel_page(){
        //     require_once( __DIR__ . '/visual-action-hooks.admin.php' );
        // }

        // public function add_settings_link( $links ) {
        //     $links[] = '<a href="' . admin_url( 'tools.php?page=' . CVAH_PREFIX . '_settings_page' ) . '">' . __('Settings') . '</a>';
        //     return $links;
        // }

        // Return an instance of this class.
		public static function get_instance () {
			// If the single instance hasn't been set, set it now.
			if ( self::$instance == null ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

    }

    add_action( 'plugins_loaded', array( 'Visual_Action_Hooks', 'get_instance' ), 0 );

}
