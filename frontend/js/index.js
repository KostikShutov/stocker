require('bootstrap');

const $ = require('jquery');
const $progressBar = $('.progress-bar');

if ($progressBar.length) {
    let waiting = false;
    let processing = false;
    setTimeout(check, 3000);

    function check() {
        $.get($progressBar.data('url-check'), [], function(response) {
            switch (response.status) {
                case 'waiting':
                    waiting = true;
                    setTimeout(check, 3000);
                    break;
                case 'processing':
                    if (waiting) {
                        location.reload();
                        break;
                    }

                    if (!processing) {
                        const intervalProgress = setInterval(function () {
                            let width = $progressBar.data('width') + 3;

                            if (width >= 100) {
                                width = 100;
                                clearInterval(intervalProgress);
                            }

                            $progressBar.data('width', width)
                            $progressBar.css('width', width + '%');
                        }, 1000);

                        processing = true;
                    }

                    setTimeout(check, 3000);
                    break;
                case 'done':
                    location.href = $progressBar.data('url-redirect');
                    break;
                default:
                    break;
            }
        });
    }
}
