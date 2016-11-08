

$(document).ready(function() {
    $('[name="SMS_NEWS_LETTER_TEXT"]').on('keyup', function () {
        var length = $('[name="SMS_NEWS_LETTER_TEXT"]').val().length;
        if (length > 60) length = "<span style='color: red;'>60 знаков!</span>";
        $('.help-block').html('Количество символов: ' + length + '/60');
    });
});