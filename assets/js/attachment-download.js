;(function($) {
    "use strict";

    $(document).ready(function() {
        const doc = $(".downloadButton");
        let course_id = doc.attr( 'data-course-id'); // Ensure your course element has data-video-id
        let attachment_id = doc.attr( 'data-attachment-id'); // Ensure your video element has data-video
        let userId = window.tlms_at_vars1.tlms_at_user_id; // Ensure this global variable is set in your theme or via WordPress AJAX
        let course_content_id = doc.attr('data-content-id');


        console.log('course_id: ' + course_id);
        console.log('attachment_id: ' + attachment_id);
        console.log('userId: ' + userId);
        console.log('course_content_id: ' + course_content_id);

        doc.on('click', function() {
            $.ajax({
                url: tlms_at_vars1.tlms_at_ajax_url,
                type: 'POST',
                data: {
                    action: 'tlms_at_track_attachment',
                    nonce: tlms_at_vars1.tlms_at_nonce,
                    attachment_id: attachment_id,
                    course_id: course_id,
                    course_content_id: course_content_id,
                    user_id: userId
                },
                success: function(response) {
                    if (!response.success) {
                        console.error('Tracking failed: ', response.data);
                    }
                },
            });
        });
    });
})(jQuery);