<?php
/**
 * Plugin Name:       TutorLMS Advanced Tracking
 * Plugin URI:        https://joydevs.com
 * Description:       Advanced tracking for TutorLMS including video analytics
 * Version:           1.1.0
 * Author:            JoyDevs
 * Author URI:        https://joydevs.com/
 * License:           GPL v2 or later
 * Text Domain:       tutorlms-advanced-tracking
 * Domain Path:       /languages/
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'TutorLMS_Advanced_Tracking' ) ) {

    /**
     * The main plugin class
     */
    final class TutorLMS_Advanced_Tracking {

        /**
         * TutorLMS_Advanced_Tracking constructor.
         */
        private function __construct() {
            $this->define_constants();

            add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
            add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
        }

        /**
         * Initializes a single instance
         */
        public static function init() {
            static $instance = false;

            if ( ! $instance ) {
                $instance = new self();
            }

            return $instance;
        }

        /**
         * Load plugin text domain
         */
        public function load_text_domain() {
            load_plugin_textdomain( 'tutorlms-advanced-tracking', false, plugin_dir_path( __FILE__ ) . 'languages/' );
        }

        /**
         * Define plugin path and url constants
         */
        public function define_constants() {
            define( 'TLMS_AT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
            define( 'TLMS_AT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
            define( 'TLMS_AT_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
            define( 'TLMS_AT_VERSION', '1.1.0' );
        }

        /**
         * Initialize the plugin
         */
        public function init_plugin() {
            require_once TLMS_AT_PLUGIN_PATH . 'autoloader.php';
            \TutorLMS_Advanced_Tracking\Init::course_tracking_setup();
        }
    }
}

/**
 * Initialize the main plugin
 *
 * @return TutorLMS_Advanced_Tracking
 */
function TutorLMS_Advanced_Tracking() {
    return TutorLMS_Advanced_Tracking::init();
}

/**
 * Kick off the plugin
 */
TutorLMS_Advanced_Tracking();






















// add_action('admin_init', 'tlms_at_create_table');
register_activation_hook(__FILE__, 'tlms_at_create_table');

function tlms_at_create_table() {
    global $wpdb;

	$table_name = $wpdb->prefix . 'tlms_at_video_progress';
	$charset_collate = $wpdb->get_charset_collate();
	$table_name_2 = $wpdb->prefix. 'tlms_at_download_count';

	$sql = "CREATE TABLE $table_name (
	    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	    course_id VARCHAR(255) NOT NULL,
	    video_id VARCHAR(255) NOT NULL,
	    course_content_id VARCHAR(255) NOT NULL,
	    lesson_title VARCHAR(255) NOT NULL,
	    user_id BIGINT(20) UNSIGNED NOT NULL,
	    date DATE NOT NULL,
	    time TIME NOT NULL,  
	    total_watch_time FLOAT NOT NULL,  
	    PRIMARY KEY (id),
	    UNIQUE KEY video_user_date (video_id, user_id, date)
	) $charset_collate;";

	$sql_2 = "CREATE TABLE $table_name_2 (
		`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	    `course_id` VARCHAR(255) NOT NULL,
	    `course_content_id` VARCHAR(255) NOT NULL,
	    `user_id` BIGINT(20) UNSIGNED NOT NULL,
	    `attachment_id` BIGINT(20) UNSIGNED NOT NULL,
	    `date` DATE NOT NULL,
	    `download_count` INT NOT NULL,
	    PRIMARY KEY (`id`),
	    UNIQUE KEY `unique_entry` (`course_id`, `course_content_id`, `user_id`, `attachment_id`, `date`)
	) $charset_collate;";
	    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	    dbDelta($sql);
	    dbDelta($sql_2);

	    // Clear cache after creating/updating the table
	    wp_cache_delete($table_name, 'transient');
	    wp_cache_delete($table_name_2, 'transient');
}













