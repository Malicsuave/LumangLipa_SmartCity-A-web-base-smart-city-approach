// Inline JS for RESIDENT registration birthdate validation only
$(document).ready(function() {
    var today = new Date();
    var minBirthDate = new Date(today.getFullYear() - 59, today.getMonth(), today.getDate());
    $('#birthdate').attr('max', minBirthDate.toISOString().split('T')[0]);

    function showBirthdateError() {
        var errorId = 'birthdate-invalid-msg';
        $('#' + errorId).remove();
        var msg = '<div id="' + errorId + '" class="invalid-feedback d-block mt-1">Residents aged 59 or older must register as senior citizens. Please select a valid birthdate.</div>';
        $('#birthdate').removeClass('is-valid').addClass('is-invalid').after(msg);
    }
    function clearBirthdateError() {
        $('#birthdate').removeClass('is-invalid is-valid');
        $('#birthdate-invalid-msg').remove();
    }
    function setBirthdateValid() {
        $('#birthdate').removeClass('is-invalid').addClass('is-valid');
        $('#birthdate-invalid-msg').remove();
    }

    $('#birthdate').on('input change', function() {
        var val = $(this).val();
        if (!val) {
            clearBirthdateError();
            return;
        }
        var birthdate = new Date(val);
        var now = new Date();
        var age = now.getFullYear() - birthdate.getFullYear();
        var birthdayThisYear = new Date(now.getFullYear(), birthdate.getMonth(), birthdate.getDate());
        if (now < birthdayThisYear) {
            age--;
        }
        if (age >= 59) {
            showBirthdateError();
        } else {
            setBirthdateValid();
        }
    });

    $('#step1Form').on('submit', function(e) {
        var val = $('#birthdate').val();
        if (!val) return true;
        var birthdate = new Date(val);
        var now = new Date();
        var age = now.getFullYear() - birthdate.getFullYear();
        var birthdayThisYear = new Date(now.getFullYear(), birthdate.getMonth(), birthdate.getDate());
        if (now < birthdayThisYear) {
            age--;
        }
        if (age >= 59) {
            showBirthdateError();
            $('#birthdate').focus();
            e.preventDefault();
            return false;
        }
        return true;
    });
});
