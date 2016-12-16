document.getElementById('child_birthday').onblur = function() {
    updateAge($('input[name="child_birthday"]'));
}

document.getElementById('child_birthday').onchange = function() {
    updateAge($('input[name="child_birthday"]'));
}

alert('hello');

updateAge($('input[name="child_birthday"]'));

function updateAge($e) {
    $.get('/api/datetime/age?birthday=' + $($e).val(), function (res) {
        let age = res;

        if (0 < age.length) {
            $('#child_age').text(age);
        }
    }); 
}