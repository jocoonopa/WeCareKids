$('#child_birthday').datetimepicker({
    format: 'YYYY-MM-DD',
    locale: 'zh-cn',
    viewMode: 'years',
    maxDate: moment()
});

$('#child_birthday').blur(function () {
    let birthday = new Date($(this).val());

    let age = calculateAge(birthday);

    if (0 < age) {
        $('#child_age').text(age + '歲');
    }
}).blur();