<?php

namespace TutorLMS_Advanced_Tracking;

/**
 * Class Tracking
 *
 * @package TutorLMS_Advanced_Tracking
 */
class Tracking {

    /**
     * Tracking constructor.
     */
    public function __construct() {
        add_filter('tutor_single_lesson_video', array( $this, 'custom_tutor_single_lesson_video' ), 10, 3);

        add_action('wp_ajax_tlms_at_track_video', array($this, 'track_video'));
        add_action('wp_ajax_nopriv_tlms_at_track_video', array($this, 'track_video'));

    }



    public function custom_tutor_single_lesson_video($content, $video_info, $source_key) {
        ob_start();
        require_once TLMS_AT_PLUGIN_PATH . 'includes/views/html5.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * Track video progress
     */
    public function track_video() {
        // Security check
        check_ajax_referer('tlms_at_track_nonce', 'nonce');



        $video_id = isset($_POST['video_id']) ? sanitize_text_field($_POST['video_id']) : '';
        $total_watch_time = isset($_POST['total_watch_time']) ? floatval($_POST['total_watch_time']/1000) : 0;
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $course_id = isset($_POST['course_id']) ? sanitize_text_field($_POST['course_id']) : '';

        // ray('video_id', $video_id);

        // ray('total_watch_time', $total_watch_time);

        // ray('user_id', $user_id);

        // ray('course_id', $course_id);

        if ($video_id && $user_id) {
            // Generate current date and time
            $date = date('Y-m-d');
            $time = date('H:i:s');

            // Save or update video progress to the database
            global $wpdb;
            $table_name = $wpdb->prefix . 'tlms_at_video_progress';
            $existing = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE video_id = %s AND user_id = %d AND date = %s",
                $video_id, $user_id, $date
            ));

            if ($existing) {
                // Update existing record
                $new_total_watch_time = $existing->total_watch_time + $total_watch_time;
                $wpdb->update(
                    $table_name,
                    array('total_watch_time' => $new_total_watch_time, 'time' => $time),
                    array('video_id' => $video_id, 'user_id' => $user_id, 'date' => $date),
                    array('%f', '%s'),
                    array('%s', '%d', '%s')
                );
            } else {
                // Insert new record
                $wpdb->insert(
                    $table_name,
                    array(
                        'course_id' => $course_id, 
                        'video_id' => $video_id,
                        'user_id' => $user_id,
                        'date' => $date,
                        'time' => $time,
                        'total_watch_time' => $total_watch_time,
                    ),
                    array(

                        '%s',
                        '%s',
                        '%d',
                        '%s',
                        '%s',
                        '%f'
                    )
                );
            }
        }

        wp_send_json_success();
    }


    // public function track_video() {
    //     // Check the nonce for security
    //     check_ajax_referer('tlms_at_nonce', 'nonce');
    
    //     // Get the data from the request
    //     $video_id = isset($_POST['video_id']) ? sanitize_text_field($_POST['video_id']) : '';
    //     $user_id = isset($_POST['user_id']) ? absint($_POST['user_id']) : 0;
    //     $total_watch_time = isset($_POST['total_watch_time']) ? floatval($_POST['total_watch_time']/1000) : 0;
    //     $course_id = isset($_POST['course_id']) ? sanitize_text_field($_POST['course_id']) : '';
    
    //     if (empty($video_id) || empty($user_id) || $total_watch_time <= 0) {
    //         wp_send_json_error(array('message' => 'Invalid data.'));
    //     }

    //     ray('video_id', $video_id);
    //     ray('user_id', $user_id);
    //     ray('total_watch_time', $total_watch_time);
    //     ray('course_id', $course_id);
        
    
    //     global $wpdb;
    //     $table_name = $wpdb->prefix . 'tlms_at_video_progress';
    
    //     // Insert or update the data in the database
    //     $wpdb->replace(
    //         $table_name,
    //         array(
    //             'course_id' => $course_id,
    //             'video_id' => $video_id,
    //             'user_id' => $user_id,
    //             'total_watch_time' => $total_watch_time,
    //             'date' => current_time('Y-m-d'), // current date in YYYY-MM-DD format
    //             'time' => current_time('H:i:s')  // current time in HH:MM:SS format
    //         ),
    //         array(
    //             '%s',
    //             '%s',
    //             '%d',
    //             '%f',
    //             '%s',
    //             '%s'
    //         )
    //     );
    
    //     if ($wpdb->last_error) {
    //         wp_send_json_error(array('message' => 'Database error: ' . $wpdb->last_error));
    //     } else {
    //         wp_send_json_success(array('message' => 'Video watch time tracked successfully.'));
    //     }
    // }
    
}
