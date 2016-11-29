var elem = document.querySelector('[name="is_open"]');
var init = new Switchery(elem, { color: '#41b7f1' });

$(function() {
    var $dateRange = $('input.daterange');
    $dateRange.daterangepicker({
        locale: {
            format: 'YYYY-MM-DD'
        },
        startDate: $dateRange.data('open'),
        endDate: $dateRange.data('close')
    },function(start, end, label) {
        $('input[name="open_at"]').val(start.format('YYYY-MM-DD'));
        $('input[name="close_at"]').val(end.format('YYYY-MM-DD'));
    });
});