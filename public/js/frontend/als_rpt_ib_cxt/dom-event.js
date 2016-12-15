$('#child_birthday').datetimepicker({
    format: 'YYYY-MM-DD',
    locale: 'zh-cn',
    viewMode: 'years',
    maxDate: moment()
}).val($('#child_birthday').data('birthday'));

$('#child_birthday').blur(function () {
    $.get('/api/datetime/age?birthday=' + $(this).val(), function (res) {
        let age = res;

        if (0 < age.length) {
            $('#child_age').text(age);
        }
    }); 
}).blur();