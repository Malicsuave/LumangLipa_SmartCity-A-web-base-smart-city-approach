#!/bin/bash

# Live server details
SERVER="root@72.60.78.167"
REMOTE_PATH="/home/lumanglipa.com/public_html"

echo "Uploading modified files to live server..."

# Upload Console Command
scp app/Console/Commands/TestBladeRenderCommand.php ${SERVER}:${REMOTE_PATH}/app/Console/Commands/

# Upload Controllers
scp app/Http/Controllers/BlotterComplaintController.php ${SERVER}:${REMOTE_PATH}/app/Http/Controllers/
scp app/Http/Controllers/DocumentGeneratorController.php ${SERVER}:${REMOTE_PATH}/app/Http/Controllers/
scp app/Http/Controllers/DocumentRequestController.php ${SERVER}:${REMOTE_PATH}/app/Http/Controllers/
scp app/Http/Controllers/HealthServiceController.php ${SERVER}:${REMOTE_PATH}/app/Http/Controllers/

# Upload Model
scp app/Models/DocumentRequest.php ${SERVER}:${REMOTE_PATH}/app/Models/

# Upload Services
scp app/Services/DocumentPdfService.php ${SERVER}:${REMOTE_PATH}/app/Services/
scp app/Services/DocumentPdfServiceWithQr.php ${SERVER}:${REMOTE_PATH}/app/Services/

# Upload Views - Templates (with embedded fonts)
scp resources/views/documents/templates/certificate-of-relationship.blade.php ${SERVER}:${REMOTE_PATH}/resources/views/documents/templates/
scp resources/views/documents/templates/barangay-clearance.blade.php ${SERVER}:${REMOTE_PATH}/resources/views/documents/templates/
scp resources/views/documents/templates/certificate-of-residency-fixed.blade.php ${SERVER}:${REMOTE_PATH}/resources/views/documents/templates/
scp resources/views/documents/templates/cerficate-of-no-income.blade.php ${SERVER}:${REMOTE_PATH}/resources/views/documents/templates/
scp resources/views/documents/templates/certificate-of-indigency-original.blade.php ${SERVER}:${REMOTE_PATH}/resources/views/documents/templates/

# Upload Views - Public
scp resources/views/public/forms/document-request.blade.php ${SERVER}:${REMOTE_PATH}/resources/views/public/forms/
scp resources/views/public/services.blade.php ${SERVER}:${REMOTE_PATH}/resources/views/public/

# Upload Views - Admin
scp resources/views/admin/residents/services.blade.php ${SERVER}:${REMOTE_PATH}/resources/views/admin/residents/

# Upload Migrations
scp database/migrations/2025_11_01_165250_add_income_occupation_to_document_requests_table.php ${SERVER}:${REMOTE_PATH}/database/migrations/
scp database/migrations/2025_11_01_210610_add_relationship_fields_to_document_requests_table.php ${SERVER}:${REMOTE_PATH}/database/migrations/

echo "Upload complete!"
echo ""
echo "Now SSH into the server and run these commands:"
echo "ssh ${SERVER}"
echo "cd ${REMOTE_PATH}"
echo "php artisan view:clear"
echo "php artisan cache:clear"
echo "php artisan config:clear"

