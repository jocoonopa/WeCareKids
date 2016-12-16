// $('#child_birthday').datetimepicker({
//     format: 'YYYY-MM-DD',
//     locale: 'zh-cn',
//     viewMode: 'years',
//     maxDate: moment()
// }).val($('#child_birthday').data('birthday'));

$('input[name="child_birthday"]').blur(function () {
    updateAge($(this));
}).blur();

$('input[name="child_birthday"]').change(function () {
    updateAge($(this));
});

function updateAge($e) {
    $.get('/api/datetime/age?birthday=' + $($e).val(), function (res) {
        let age = res;

        if (0 < age.length) {
            $('#child_age').text(age);
        }
    }); 
}