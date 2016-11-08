$(document).ready(function() {

    var cssClass = 'alert alert-danger';
    if (typeof nw_error != 'undefined' && !nw_error)
        cssClass = 'alert alert-success';

    if (typeof msg_newsl != 'undefined' && msg_newsl)
    {
        $('#columns').prepend('<div class="clearfix"></div><p class="' + cssClass + '"> ' + alert_smsnewsletter + '</p>');
        $('html, body').animate({scrollTop: $('#columns').offset().top}, 'slow');
    }
});