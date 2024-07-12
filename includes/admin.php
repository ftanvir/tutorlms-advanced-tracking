<?php


namespace TutorLMS_Advanced_Tracking;


class Admin {

    public function __construct() {
        add_action('wp_ajax_tlms_at_get_video_watch_time_data', array($this, 'get_video_watch_time_data'));
        
        if ( isset( $_GET['student_id'] ) ) {
            add_filter('tutor_report_student_profile_template_path', array($this, 'custom_student_profile_template_path'));
        
        }
    }

    public function get_video_watch_time_data() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'tlms_at_video_progress';

        // Get the last 7 days of data
        $start_date = date('Y-m-d', strtotime('-7 days'));
        $end_date = date('Y-m-d');

        // Get the data for the past 7 days
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT video_id, SUM(total_watch_time) as total_watch_time
            FROM $table_name
            WHERE date BETWEEN %s AND %s
            GROUP BY video_id",
            $start_date, $end_date
        ));

        $labels = [];
        $values = [];

        foreach ($results as $row) {
            $labels[] = $row->video_id;
            $values[] = $row->total_watch_time / 3600;  // Convert seconds to hours
        }

        wp_send_json_success(array(
            'labels' => $labels,
            'values' => $values
        ));
    }

    public function custom_student_profile_template_path($path) {
        // Change the path as needed
        $new_path = TLMS_AT_PLUGIN_PATH . 'includes/views/student-profile.php';
        return $new_path;
    }

}