require('bootstrap');

const $ = require('jquery');
const $statusProcessing = $('h4[data-status-processing]');

if ($statusProcessing.length) {
    setTimeout(check, 3000);

    function check() {
        $.get(
            $statusProcessing.data('url-check'),
            [],
            function(response) {
                if (response.success) {
                    location.href = $statusProcessing.data('url-redirect');
                } else {
                    setTimeout(check, 3000);
                }
            }
        )
    }

    const $statusProgress = $('.progress-bar');
    const intervalProgress = setInterval(function() {
        let width = $statusProgress.data('width') + 3;

        if (width >= 100) {
            width = 100;
            clearInterval(intervalProgress);
        }

        $statusProgress.data('width', width)
        $statusProgress.css('width', width + '%');
    }, 1000);
}
