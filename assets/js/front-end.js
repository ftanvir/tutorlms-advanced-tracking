;(function($) {
    "use strict";

    $(document).ready(function() {
        const video = document.getElementById('tutorlms-advanced-tracking');
        let playStartTime = 0;
        let totalPlayTime = 0;
        let course_id = $('#tutorlms-advanced-tracking').data('course-id'); // Ensure your course element has data-video-id
        let videoId = $('#tutorlms-advanced-tracking').data('video-id'); // Ensure your video element has data-video-id
        let userId = window.tlms_at_vars.tlms_at_user_id; // Ensure this global variable is set in your theme or via WordPress AJAX
        let lessonTitle =video.getAttribute('lesson-title');
        let courseContentId = video.getAttribute('data-course-content-id');

        

        $(video).on('play', function() {
            playStartTime = new Date().getTime();
            
        });

        $(video).on('pause', function() {
            if (playStartTime) {
                totalPlayTime += new Date().getTime() - playStartTime;
                playStartTime = 0; 
                
            }
        });

        $(video).on('ended', function() {
            if (playStartTime) {
                totalPlayTime += new Date().getTime() - playStartTime;
                playStartTime = 0; 
            }
            
        });

        $(window).on('beforeunload', function() {
            if (playStartTime) {
                totalPlayTime += new Date().getTime() - playStartTime;
            }
            

            $.ajax({
                url: tlms_at_vars.tlms_at_ajax_url,
                type: 'POST',
                data: {
                    action: 'tlms_at_track_video',
                    nonce: tlms_at_vars.tlms_at_nonce,
                    video_id: videoId,
                    course_id: course_id,
                    course_content_id: courseContentId,
                    total_watch_time: totalPlayTime,  // Send total watch time
                    lesson_title: lessonTitle,
                    user_id: userId
                },
                success: function(response) {
                    if (!response.success) {
                        console.error('Tracking failed: ', response.data);
                    }
                },
                error: function() {
                    console.error('AJAX request failed.');
                }
            });

        });
            
            
    });
})(jQuery);
