// $('#child_birthday').datetimepicker({
//     format: 'YYYY-MM-DD',
//     locale: 'zh-cn',
//     viewMode: 'years',
//     maxDate: moment()
// }).val($('#child_birthday').data('birthday'));

// $('input[name="child_birthday"]').blur(function () {
//     updateAge($(this));
// }).blur();

document.getElementById('child_birthday').onblur = function() {
    updateAge($('input[name="child_birthday"]'));
}

document.getElementById('child_birthday').onchange = function() {
    updateAge($('input[name="child_birthday"]'));
}

updateAge($('input[name="child_birthday"]'));

function updateAge($e) {
    $.get('/api/datetime/age?birthday=' + $($e).val(), function (res) {
        let age = res;

        if (0 < age.length) {
            $('#child_age').text(age);
        }
    }); 
}