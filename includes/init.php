<?php

namespace TutorLMS_Advanced_Tracking;

/**
 * Class Init
 *
 * @package TutorLMS_Advanced_Tracking
 */
class Init {

    /**
     * Setup the plugin
     */
    public static function course_tracking_setup() {
        // Load the assets
        new Admin();
        new Tracking();
        new Assets();

    }

    /**
     * Register actions and filters
     */
    private static function register_hooks() {

    }
}
