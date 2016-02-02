/*!
 * Form Validation
 * Copyright 2016 Muhmmad Gilang Januar
 * Licensed under the MIT license
 */

$(document).ready(function () {
    $('form.use-validation').submit(function (e) {
        e.preventDefault()

        // default action after submit form
        var $form = $(this);
        $(this).find('.form-group').removeClass('has-error')
        if ($(this).find('.form-group .error').length)
            $(this).find('.form-group .error').remove()

        // send ajax request for get validation form
        $.post($(this).find('input[name=_validation]').val(), $('input[name!=_validation]', this).serialize()).done(function (data) {
            data = JSON.parse(data);
            var valid = true;

            // parse data to models
            for (var model in data) {

                // parse model to fields
                for (var field in data[model]) {

                    // get .form-group input field
                    var $field = $('[name="' + model + '[' + field + ']' + '"]').parents('.form-group')

                    // if found error
                    if (data[model][field] != 'undefined' && data[model][field][0] != 'undefined') {
                        valid = false
                        $field.addClass('has-error')

                        // if error element was exist
                        if ($field.find('.error').length) {
                            $field.find('.error').html('<span class="help-block error">'+data[model][field][0]+'</span>')
                        }
                        else {
                            // create error element
                            $field.append('<span class="help-block error">'+data[model][field][0]+'</span>')
                        }
                    }
                }
            }

            // submit form if has no error
            if (valid)
                $form.unbind('submit').submit()
        })
    })
})