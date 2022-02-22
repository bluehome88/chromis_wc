function wfit(v) {
    switch (v) {
        case 'unfit':
            $('.suitable').addClass('hide');
            $('.unfit, .unfitsuit').removeClass('hide');
            $('.suit').removeClass('required');
            $('.unfi, .unfisuit').addClass('required');
            if (o_def.other == 'OTHER') {
                $('.other').removeClass('hide');
            }
            break;
        case 'suitable':
            $('.unfit').addClass('hide');
            $('.suitable, .unfitsuit').removeClass('hide');
            $('.unfi').removeClass('required');
            $('.suit, .unfisuit').addClass('required');
            if (o_def.other == 'OTHER') {
                $('.other').removeClass('hide');
            }
            break;
            break;
        case 'fit':
            $('.suit, .unfisuit, .unfit').removeClass('required');
            $('.suitable, .unfit, .unfitsuit').addClass('hide');
            if (o_def.other == 'OTHER') {
                $('.other').removeClass('hide');
            }
            break;
    }
}

jQuery(document).ready(function ($) {
    jQuery.validator.methods.date = function (value, element) {
        return this.optional(element) || !/Invalid|NaN/.test(new Date(value)) || /^(\d+)\/(\d+)\/(\d{2,})$/.test(value);
    }
    $('.date').datepicker({
        dateFormat: 'dd/mm/yy',
        onSelect: function () {
            $(this).blur();
        },
        onClose: function () {
            $(this).blur();
            $('.btn-primary').removeAttr('disabled');
        }
    });

    if (o_def.consult == 'Initial') {
        $('.Initial, .unfitsuit').removeClass('hide');
        $('#output-0').prop('checked', true);
    } else {
        $('.Initial').addClass('hide');
        if (o_def.consult != 'fit') {
            $('.unfitsuit').removeClass('hide');
        }
        $('#output-1').prop('checked', true);
    }

    $('.suitable, .unfit, .unfitsuit, .other').addClass('hide');
    wfit(o_def.wfit);


    $('input:radio[name=Consult]').click(function () {
        if ($(this).val() == 'Initial') {
            $('.Initial').removeClass('hide');
            $('#output-0').prop('checked', true);
        } else {
            $('.Initial').addClass('hide');
            $('#output-1').prop('checked', true);
        }
    });

    if ($('input:radio[name=OTHER_RESTRICTIONS]').val() == 'OTHER') {
        $('.other').removeClass('hide');
    } else {
        $('.other').addClass('hide');
    }
    $('input:radio[name=OTHER_RESTRICTIONS]').click(function () {
        if ($(this).val() == 'OTHER') {
            $('.other').removeClass('hide');
        } else {
            $('.other').addClass('hide');
        }
    });

    $('input:radio[name=WFit]').click(function () {
        wfit($(this).val());
    });

    var val = $('#DR_WCMC').validate();

    $(':input, :radio, :checkbox').blur(function () {
        if ($('#DR_WCMC').valid()) {
            $('.btn-primary').removeAttr('disabled');
        }
    });

    $('textarea:not(.sml,.exsml,.vsml)').jqEasyCounter({
        'maxChars': 274,
        'maxCharsWarning': 264
    });

    $('textarea.sml').jqEasyCounter({
        'maxChars': 93,
        'maxCharsWarning': 83
    });

    $('textarea.exsml').jqEasyCounter({
        'maxChars': 71,
        'maxCharsWarning': 61
    });

    $('textarea.vsml').jqEasyCounter({
        'maxChars': 34,
        'maxCharsWarning': 24
    });

    $(document).click(function () {
        if ($('#DR_WCMC').valid()) {
            $('.btn-primary').removeAttr('disabled');
        }
    });

    var obj = {};
    obj['Avoid:'] = 'Avoid:';
    $('.atext:checked').each(function () {
        obj[$(this).val()] = $(this).val();
    });
    str_out();

    $('.atext').click(function () {
        var u = 0;
        if ($(this).is(':checked')) {
            obj[$(this).val()] = $(this).val();
        } else {
            delete obj[$(this).val()];
        }
        str_out();
    });

    function str_out() {
        var c = 0;
        var s = '';
        var comma = ' ';
        for (var i in obj) {
            if (i == 'Avoid:' || c == 1) {
                comma = ' ';
            } else {
                comma = ', ';
            }
            s += comma + obj[i];
            c++;
        }
        if (c > 1) {
            s = $.trim(s);
            if (s.length > 93) {
                s = s.substr(0, 93);
            }
            $('.otext').val(s);
        } else {
            $('.otext').val('');
        }
    }
});
