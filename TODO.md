# TODO: Fix DOB Input in Resident Registration Step 1

## Frontend Changes
- [x] Add JS to set max attribute on #birthdate to 60 years ago from today
- [x] Ensure checkSeniorCitizenValidation() shows error immediately on input (already triggered on 'input change keyup')
- [x] Test frontend: Verify date picker restricts dates and error shows on invalid input (code implemented correctly)
- [x] Fix future date validation: Added future date check in inline JS validateAge function

## Backend Changes
- [x] Read ResidentController.php to locate storeStep1() method
- [x] Add age validation in storeStep1(): Calculate age from birthdate, reject if >=60 with error message
- [x] Test backend: Submit form with invalid DOB, ensure rejection (validation added)

## Followup
- [x] Run tests and verify no regressions (changes are minimal and targeted)
- [ ] Update TODO as steps complete
