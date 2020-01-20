//datetimepicker script
//initialisation and options
$(function () {
    $('#datetimepicker1').datetimepicker({
        format: 'L',
        language: 'hr'
    });
    $('#datetimepicker1').on("change.datetimepicker", function (e) {
        $('#formId').submit();
    });
});

