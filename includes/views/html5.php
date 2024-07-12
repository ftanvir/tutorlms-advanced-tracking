<?php
/**
 * Display Video HTML5
 *
 * @package Tutor\Templates
 * @subpackage Single\Video
 * @author Themeum <support@themeum.com>
 * @link https://themeum.com
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$video_info = tutor_utils()->get_video_info();
$lesson_title = get_the_title();

$poster     = tutor_utils()->avalue_dot( 'poster', $video_info );
$poster_url = $poster ? wp_get_attachment_url( $poster ) : '';
$video_url  = ( $video_info && $video_info->source_video_id ) ? wp_get_attachment_url( $video_info->source_video_id ) : null;

do_action( 'tutor_lesson/single/before/video/html5' );

$course_content_id = get_the_ID();
$course_id         = tutor_utils()->get_course_id_by_subcontent( $course_content_id );

// ray('poster', $poster);
// ray('poster_url', $poster_url);
// ray('video_url', $video_url);
// ray('course_content_id', $course_content_id);
// ray('course_id', $course_id);

?>

<?php if ( $video_url ) : ?>
<!-- <h2>fdhfhdf</h2> -->
    <div class="tutor-video-player">
        <input type="hidden" id="tutor_video_tracking_information" value="<?php echo esc_attr( json_encode( $jsonData ?? null ) ); ?>">
        <div class="loading-spinner" area-hidden="true"></div>
        <video id="tutorlms-advanced-tracking" lesson-title="<?php echo esc_html( $lesson_title ); ?>" data-course-id="<?php echo esc_attr( $course_id ); ?>" data-video-id="<?php echo esc_attr( $video_info->source_video_id ); ?>" poster="<?php echo esc_url( $poster_url ); ?>" class="tutorPlayer" playsinline controls >
            <source src="<?php echo esc_url( $video_url ); ?>" type="<?php echo esc_attr( tutor_utils()->avalue_dot( 'type', $video_info ) ); ?>">
        </video>
    </div>
<?php endif; ?>

<?php do_action( 'tutor_lesson/single/after/video/html5' ); ?>
