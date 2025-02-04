$(document).ready(function () {
    if (!(
        // browser explicitly reports no PDF support
        navigator.pdfViewerEnabled === false
        // or browser is mobile
        || /Android|iPad|iPhone|Mobi|Tablet/.test(navigator.userAgent)
        // or brpwser is iPad in desktop mode
        || navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1
    )) {
        return;
    }
    $('iframe.pdf-embed').replaceWith(function () {
        var iframe = $(this);
        return $('<a>', {
            href: iframe.attr('src'),
            text: 'Open PDF',
        });
    });
});
