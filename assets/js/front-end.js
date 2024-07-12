// ;
// (function($) {
//     "use strict";


//     if (videojs) {
//         var player = videojs('tutorlms-advanced-tracking'); // Ensure your video element has an id of 'tutorlms-advanced-tracking'

//         var course_id = $('#tutorlms-advanced-tracking').data('course-id'); // Ensure your course element has data-video-id
//         var videoId = $('#tutorlms-advanced-tracking').data('video-id'); // Ensure your video element has data-video-id
//         var userId = window.tlms_at_user_id; // Ensure this global variable is set in your theme or via WordPress AJAX
//         var totalWatchTime = 0;  // To keep track of the total time watched
//         var lastTime = 0;
//         console.log('Course ID: ', course_id);
//         console.log('Video ID: ', videoId);
//         console.log('User ID: ', userId);

//         // Track video progress periodically
//         setInterval(function() {
//             trackVideoProgress();
//         }, 30000); // Every 30 seconds

//         // Track video progress when paused or ended
//         player.on('pause', trackVideoProgress);
//         player.on('ended', trackVideoProgress);

//         function trackVideoProgress() {
//             var currentTime = player.currentTime();
//             totalWatchTime += (currentTime - lastTime);
//             lastTime = currentTime;

//             console.log('Total watch time:', totalWatchTime, 'seconds');


//             // if (videoId && userId) {
//             //     $.ajax({
//             //         url: tlms_at_vars.tlms_at_ajax_url,
//             //         type: 'POST',
//             //         data: {
//             //             action: 'tlms_at_track_video',
//             //             nonce: tlms_at_vars.tlms_at_nonce,
//             //             video_id: videoId,
//             //             total_watch_time: totalWatchTime,  // Send total watch time
//             //             user_id: userId
//             //         },
//             //         success: function(response) {
//             //             if (!response.success) {
//             //                 console.error('Tracking failed: ', response.data);
//             //             }
//             //         },
//             //         error: function() {
//             //             console.error('AJAX request failed.');
//             //         }
//             //     });
//             // }
//         }
//     }
// })(jQuery);

// ;(function($) {
//     "use strict";

//     $(document).ready(function() {
//         const video = document.getElementById('tutorlms-advanced-tracking');
//         let playStartTime = 0;
//         let totalPlayTime = 0;

//         $(video).on('play', function() {
//             playStartTime = new Date().getTime();
//             console.log('Video started playing at:', playStartTime);
//         });

//         $(video).on('pause', function() {
//             if (playStartTime) {
//                 totalPlayTime += new Date().getTime() - playStartTime;
//                 playStartTime = 0; 
//                 console.log('Total play time after pause:', totalPlayTime / 1000, 'seconds');
//             }
//         });

//         $(video).on('ended', function() {
//             if (playStartTime) {
//                 totalPlayTime += new Date().getTime() - playStartTime;
//                 playStartTime = 0; 
//             }
//             console.log('Total play time after end:', totalPlayTime / 1000, 'seconds');
//         });

//         $(window).on('beforeunload', function() {
//             if (playStartTime) {
//                 totalPlayTime += new Date().getTime() - playStartTime;
//             }
//             console.log('Total play time before unload:', totalPlayTime / 1000, 'seconds');
//         });


//         $.post(tlms_at_vars.tlms_at_ajax_url, {
//             action: 'tlms_at_track_video',
//             nonce: tlms_at_vars.tlms_at_nonce,
//             video_id: $('#tutorlms-advanced-tracking').data('video-id'),
//             total_watch_time: totalPlayTime / 1000,
//             user_id: window.tlms_at_user_id
//         }, function(response) {
//             if (!response.success) {
//                 console.error('Tracking failed: ', response.data);
//             }
//         });

//     });
// })(jQuery);

;(function($) {
    "use strict";

    $(document).ready(function() {
        const video = document.getElementById('tutorlms-advanced-tracking');
        let playStartTime = 0;
        let totalPlayTime = 0;
        let course_id = $('#tutorlms-advanced-tracking').data('course-id'); // Ensure your course element has data-video-id
        let videoId = $('#tutorlms-advanced-tracking').data('video-id'); // Ensure your video element has data-video-id
        let userId = window.tlms_at_vars.tlms_at_user_id; // Ensure this global variable is set in your theme or via WordPress AJAX

        // console.log('Course ID: ', course_id);
        // console.log('Video ID: ', videoId);
        // console.log('User ID: ', userId);


        $(video).on('play', function() {
            playStartTime = new Date().getTime();
            console.log('Video started playing at:', playStartTime);
        });

        $(video).on('pause', function() {
            if (playStartTime) {
                totalPlayTime += new Date().getTime() - playStartTime;
                playStartTime = 0; 
                console.log('Total play time after pause:', totalPlayTime / 1000, 'seconds');
            }
        });

        $(video).on('ended', function() {
            if (playStartTime) {
                totalPlayTime += new Date().getTime() - playStartTime;
                playStartTime = 0; 
            }
            console.log('Total play time after end:', totalPlayTime / 1000, 'seconds');
        });

        $(window).on('beforeunload', function() {
            if (playStartTime) {
                totalPlayTime += new Date().getTime() - playStartTime;
            }
            console.log('Total play time before unload:', totalPlayTime / 1000, 'seconds');

            $.ajax({
                url: tlms_at_vars.tlms_at_ajax_url,
                type: 'POST',
                data: {
                    action: 'tlms_at_track_video',
                    nonce: tlms_at_vars.tlms_at_nonce,
                    video_id: videoId,
                    course_id: course_id,
                    total_watch_time: totalPlayTime,  // Send total watch time
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
