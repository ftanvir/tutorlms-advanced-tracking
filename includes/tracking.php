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
		add_filter( 'tutor_single_lesson_video', array( $this, 'custom_tutor_single_lesson_video' ), 10, 3 );

		add_action( 'wp_ajax_tlms_at_track_video', array( $this, 'track_video' ) );
		add_action( 'wp_ajax_nopriv_tlms_at_track_video', array( $this, 'track_video' ) );

		add_filter( "tutor-attachments-template-change", array( $this, "attachments_template_change" ), 10, 1 );

		add_filter( 'tutor_global/attachment-view', array( $this, 'custom_tutor_attachment_view' ), 10, 2 );

		add_action('wp_ajax_tlms_at_track_attachment', array($this, 'track_attachment_download'));
		add_action('wp_ajax_nopriv_tlms_at_track_attachment', array($this, 'track_attachment_download'));

	}

	public function custom_tutor_single_lesson_video( $content, $video_info, $source_key ) {
		ob_start();
		require_once TLMS_AT_PLUGIN_PATH . 'includes/views/html5.php';
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	public function custom_tutor_attachment_view( $attachment, $open_mode_view ) {
		ob_start();
		require_once TLMS_AT_PLUGIN_PATH . 'includes/views/attachment-view.php';
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * Track video progress
	 */
	public function track_video() {
		// Security check
		check_ajax_referer( 'tlms_at_track_nonce', 'nonce' );


//        $video_id = isset($_POST['video_id']) ? sanitize_text_field($_POST['video_id']) : '';
		$video_id          = isset( $_POST['video_id'] ) ?? sanitize_text_field( $_POST['video_id'] );
		$total_watch_time  = isset( $_POST['total_watch_time'] ) ? floatval( $_POST['total_watch_time'] / 1000 ) : 0;
		$user_id           = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : 0;
		$course_id         = isset( $_POST['course_id'] ) ? sanitize_text_field( $_POST['course_id'] ) : '';
		$lesson_title      = isset( $_POST['lesson_title'] ) ? sanitize_text_field( $_POST['lesson_title'] ) : '';
		$course_content_id = isset( $_POST['course_content_id'] ) ? intval( $_POST['course_content_id'] ) : 0;

		if ( $video_id && $user_id ) {
			// Generate current date and time
			$date = date( 'Y-m-d' );
			$time = date( 'H:i:s' );

			// Save or update video progress to the database
			global $wpdb;
			$table_name = $wpdb->prefix . 'tlms_at_video_progress';
			$existing   = $wpdb->get_row( $wpdb->prepare(
				"SELECT * FROM $table_name WHERE video_id = %s AND user_id = %d AND date = %s",
				$video_id, $user_id, $date
			) );

			if ( $existing ) {
				// Update existing record
				$new_total_watch_time = $existing->total_watch_time + $total_watch_time;
				$wpdb->update(
					$table_name,
					array( 'total_watch_time' => $new_total_watch_time, 'time' => $time ),
					array(
						'video_id'          => $video_id,
						'user_id'           => $user_id,
						'date'              => $date,
						'course_id'         => $course_id,
						'course_content_id' => $course_content_id,
						'lesson_title'      => $lesson_title
					),
					array( '%f', '%s' ),
					array( '%s', '%d', '%s', '%s', '%d', '%s' )
				);
			} else {
				// Insert new record
				$wpdb->insert(
					$table_name,
					array(
						'course_id'         => $course_id,
						'video_id'          => $video_id,
						'course_content_id' => $course_content_id,
						'lesson_title'      => $lesson_title,
						'user_id'           => $user_id,
						'date'              => $date,
						'time'              => $time,
						'total_watch_time'  => $total_watch_time,
					),
					array(

						'%s',
						'%s',
						'%d',
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

	/**
	 * Track attachment download
	 */
	public function track_attachment_download() {

		//security check
//		check_ajax_referer('tlms_at_attachment_nonce', 'nonce');
//
//		$attachment_id = isset($_POST['attachment_id']) ? intval($_POST['attachment_id']) : 0;
//		$course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
//		$course_content_id = isset($_POST['course_content_id']) ? intval($_POST['course_content_id']) : 0;
//		$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
//
//		global $wpdb;
//		$table_name = $wpdb->prefix . 'tlms_at_download_count';
//
//		$existing = $wpdb->get_row($wpdb->prepare(
//			"SELECT * FROM $table_name WHERE attachment_id = %d AND user_id = %d",
//			$attachment_id, $user_id
//		));
//
//		if ($existing) {
//			// Increment download count
//			$updated = $wpdb->update(
//				$table_name,
//				array('download_count' => $existing->download_count + 1),
//				array(
//					'attachment_id' => $attachment_id,
//					'user_id' => $user_id
//				),
//				array('%d'),
//				array('%d', '%d')
//			);
//
//			if ($updated === false) {
//				// Handle error here
//				// You can log the error or show an error message
//			}
//
//		} else {
//			$inserted = $wpdb->insert(
//				$table_name,
//				array(
//					'course_id' => $course_id,
//					'course_content_id' => $course_content_id,
//					'user_id' => $user_id,
//					'attachment_id' => $attachment_id,
//					'download_count' => 1
//				),
//				array(
//					'%d',
//					'%d',
//					'%d',
//					'%d',
//					'%d'
//				)
//			);
//		}
//
//
//		wp_send_json_success();
	}


//	public function attachments_template_change( $template ) {
//		print_r( $template );
//		die();
//	}


}
