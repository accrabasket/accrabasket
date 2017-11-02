var errorFlag = false;
var error = {};
error['email'] = 'valid value required in ';
error['blank'] = 'value required in ';
error['mobileNumber'] = 'Phone number not valid in ';
function addErrorSpan(element) {
    if ($(element).next('.error').length <= 0) {
        $(element).after('<span class="error"> </span>');
    }
}

function email(value, element) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if (!emailReg.test(value)) {
        displayError('email', element);
    } else {
        $(element).next('.error').remove();
        blank(value, element);
    }
}
function blank(value, element) {
    if (trimStr(value) == '') {
        displayError('blank', element);
    } else {
        $(element).next('.error').remove();
    }
}
function mobileNumber(value, element) {
    var phoneReg = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
    if (!phoneReg.test(value)) {
        displayError('mobileNumber', element);
    } else {
        $(element).next('.error').remove();
    }
}
function displayError(type, element) {
    addErrorSpan(element);
    errorFlag = true;
    $(element).next('.error').text(error[type] + $(element).prop('title'));
}
function trimStr(value) {
    return value.replace(/^\s+|\s+$/g, '');
}

function validateFormData(formId) {
    var element, fun;
    var fields= $("#" + formId).serializeArray();
    errorFlag = false;
    $.each(fields, function (i, field) {
        element = "#" + field.name;
        fun = $(element).attr('validation');
        if (fun != '' && fun != undefined) {
            window[fun](field.value, element);
        }
    });
    if (errorFlag) {
        alert('dfd');
        return false;
    }
    alert(2);
    return true;
    }