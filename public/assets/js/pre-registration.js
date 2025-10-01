document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate age when birthdate changes
    const birthdateInput = document.getElementById('birthdate');
    if (birthdateInput) {
        birthdateInput.addEventListener('change', function() {
            const birthdate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthdate.getFullYear();
            const monthDiff = today.getMonth() - birthdate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }

            // Auto-check senior citizen if 60+
            const seniorCheckbox = document.getElementById('is_senior_citizen');
            if (seniorCheckbox && age >= 60) {
                seniorCheckbox.checked = true;
            }
        });
    }

    // Show/hide citizenship country field
    const citizenshipTypeSelect = document.getElementById('citizenship_type');
    if (citizenshipTypeSelect) {
        citizenshipTypeSelect.addEventListener('change', function() {
            const countryField = document.getElementById('citizenship_country');
            if (countryField) {
                const countryInput = countryField.closest('.material-input');
                if (this.value !== 'Filipino' && this.value !== '') {
                    if (countryInput) countryInput.style.display = 'block';
                    countryField.required = true;
                } else {
                    if (countryInput) countryInput.style.display = 'none';
                    countryField.required = false;
                }
            }
        });

        // Trigger change event on page load
        citizenshipTypeSelect.dispatchEvent(new Event('change'));
    }
});
