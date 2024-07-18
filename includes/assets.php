<?php

namespace TutorLMS_Advanced_Tracking;

/**
 * Class Assets
 *
 * @package TutorLMS_Advanced_Tracking
 */
class Assets {

    /**
     * Assets constructor.
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'front_end_enqueue' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );  // Enqueue scripts for the admin area
    }

    /**
     * Frontend CSS and JS enqueue
     */
    public function front_end_enqueue() {
        wp_enqueue_style( 'video-js-css', 'https://vjs.zencdn.net/7.20.2/video-js.css', array(), '7.20.2' ); // Video.js CSS from CDN
        wp_enqueue_style( 'tlms_at_assets_frontend', TLMS_AT_PLUGIN_URL . 'assets/css/front-end.css', null, TLMS_AT_VERSION );

        wp_enqueue_script( 'video-js-js', 'https://vjs.zencdn.net/7.20.2/video.min.js', array(), '7.20.2', true ); // Video.js JS from CDN
        wp_enqueue_script( 'tlms_at_assets_frontend', TLMS_AT_PLUGIN_URL . 'assets/js/front-end.js', array( 'jquery', 'video-js-js' ), TLMS_AT_VERSION, true );


        // Localize script for AJAX requests
        wp_localize_script( 'tlms_at_assets_frontend', 'tlms_at_vars', array(
            'tlms_at_ajax_url' => admin_url( 'admin-ajax.php' ),
            'tlms_at_nonce'    => wp_create_nonce( 'tlms_at_track_nonce' ),
            'tlms_at_user_id'  => get_current_user_id(), // Assuming you want to pass the current user ID
        ) );
    }

    /**
     * Admin CSS and JS enqueue
     */
    public function admin_enqueue() {
        //wp_enqueue_style( 'tlms_at_assets_admin', TLMS_AT_PLUGIN_URL . 'assets/css/admin.css', null, TLMS_AT_VERSION );
        wp_enqueue_script('jquery');
        wp_enqueue_script('chart-js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js', array(), null, false );

        wp_enqueue_script( 'tlms_at_assets_admin' , TLMS_AT_PLUGIN_URL . 'assets/js/admin.js',  array( 'jquery' ), TLMS_AT_VERSION, true );  // Admin JS for Chart.js
    }

}
