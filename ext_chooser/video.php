<?php

require_once(dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))) . '/config.php');

?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ensemble Video External File Chooser</title>
    <link rel="stylesheet" href="css/jquery-ui/jquery-ui.min.css?v=1.8.3">
    <link rel="stylesheet" href="css/ev-script.css?v=20130304.1">
    <link rel="stylesheet" href="css/style.css?v=1">
  </head>
  <body>
    <form>
      <fieldset>
        <input id="video" name="video" type="hidden" />
        <input name="submit" class="submit" type="submit" value="Submit" />
      </fieldset>
    </form>
    <script src="js/jquery/jquery.min.js?v=1.8.3"></script>
    <script src="js/jquery-ui/jquery-ui.min.js?v=1.8.23"></script>
    <script src="js/jquery.cookie/jquery.cookie.js?v=1.3.1"></script>
    <script src="js/lodash/lodash.min.js?v=0.9.2"></script>
    <script src="js/backbone/backbone-min.js?v=0.9.2"></script>
    <script src="js/ev-script.min.js?v=20130228"></script>
    <script type="text/javascript">
        (function($) {

            'use strict';

            var proxyPath = '<?= $CFG->wwwroot . "/repository/ensemble/ext_chooser/proxy.php" ?>',
                ensembleUrl = 'https://cloud.ensemblevideo.com',
                app = new EV.EnsembleApp({
                ensembleUrl: ensembleUrl,
                authId: 'ev-moodle',
                authPath: '<?= $CFG->wwwroot ?>',
                pageSize: 100,
                scrollHeight: 300,
                proxyPath: proxyPath,
                urlCallback: function(url) {
                    return proxyPath + '?request=' + encodeURIComponent(url);
                },
            });

            var $form = $('form'),
                $video = $('#video'),
                $submit = $('.submit').hide();

            var submitHandler = function(e) {
              var video = JSON.parse($video.val());
              window.parent.M.core_filepicker.select_file({
                  // Lame hack so file is accepted by Moodle
                  title: encodeURIComponent(video.content.Title + '.avi'),
                  source: '//plugin.moodle.ensemblevideo.com?' + $.param($video.val()),
                  thumbnail: video.content.ThumbnailUrl
              });
              e.preventDefault;
            };

            $submit.click(submitHandler);

            app.appEvents.bind('fieldUpdated', function($field, value) {
                // if ($field[0].id === 'video') {
                    if (value) {
                        $submit.show();
                    } else {
                        $submit.hide();
                    }
                // } else if ($field[0].id === 'playlist') {
                //     if (value) {
                //         app.handleEmbed($playlistEmbed, new EV.PlaylistSettings(value));
                //     } else {
                //         $playlistEmbed.html('');
                //     }
                // }
            });

            $(document).ready(function() {
                app.handleField($('#video').parent(), new EV.VideoSettings(), '#video');
            });

        }(jQuery));
    </script>
  </body>
</html>
