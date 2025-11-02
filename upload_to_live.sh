#!/bin/bash

# =============================================================================
# SCP Upload Script for Live Server
# =============================================================================
# Replace the following with your actual credentials:
# - USERNAME: Your SSH username (e.g., root, admin, etc.)
# - IP_ADDRESS: Your server IP address (from the prompt: 72.60.78.167)
# - REMOTE_PATH: The path to your Laravel project on the live server
# =============================================================================

USERNAME="root"
IP_ADDRESS="72.60.78.167"
REMOTE_PATH="/home/barangaylumanglipa.tech/public_html"

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}Uploading Changes to Live Server${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""
echo -e "${GREEN}Server: ${USERNAME}@${IP_ADDRESS}${NC}"
echo -e "${GREEN}Remote Path: ${REMOTE_PATH}${NC}"
echo ""

# Upload PHP Controllers
echo -e "${BLUE}Uploading Controllers...${NC}"
scp app/Console/Commands/TestBladeRenderCommand.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/app/Console/Commands/
scp app/Http/Controllers/BlotterComplaintController.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/app/Http/Controllers/
scp app/Http/Controllers/DocumentGeneratorController.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/app/Http/Controllers/
scp app/Http/Controllers/DocumentRequestController.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/app/Http/Controllers/
scp app/Http/Controllers/HealthServiceController.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/app/Http/Controllers/

# Upload Models
echo -e "${BLUE}Uploading Models...${NC}"
scp app/Models/DocumentRequest.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/app/Models/

# Upload Services
echo -e "${BLUE}Uploading Services...${NC}"
scp app/Services/DocumentPdfService.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/app/Services/
scp app/Services/DocumentPdfServiceWithQr.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/app/Services/

# Upload Blade Templates - Document Templates
echo -e "${BLUE}Uploading Document Templates...${NC}"
scp resources/views/documents/templates/cerficate-of-no-income.blade.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/resources/views/documents/templates/
scp resources/views/documents/templates/certificate-of-relationship.blade.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/resources/views/documents/templates/

# Upload Public Forms
echo -e "${BLUE}Uploading Public Forms...${NC}"
scp resources/views/public/forms/document-request.blade.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/resources/views/public/forms/

# Upload Public Pages
echo -e "${BLUE}Uploading Public Pages...${NC}"
scp resources/views/public/services.blade.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/resources/views/public/

# Upload Admin Pages
echo -e "${BLUE}Uploading Admin Pages...${NC}"
scp resources/views/admin/residents/services.blade.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/resources/views/admin/residents/

# Upload Database Migrations
echo -e "${BLUE}Uploading Database Migrations...${NC}"
scp database/migrations/2025_11_01_165250_add_income_occupation_to_document_requests_table.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/database/migrations/
scp database/migrations/2025_11_01_210610_add_relationship_fields_to_document_requests_table.php ${USERNAME}@${IP_ADDRESS}:${REMOTE_PATH}/database/migrations/

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Upload Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${BLUE}Next steps on the live server:${NC}"
echo -e "1. SSH into the server: ${GREEN}ssh ${USERNAME}@${IP_ADDRESS}${NC}"
echo -e "2. Navigate to project: ${GREEN}cd ${REMOTE_PATH}${NC}"
echo -e "3. Run migrations: ${GREEN}php artisan migrate${NC}"
echo -e "4. Clear cache: ${GREEN}php artisan view:clear && php artisan cache:clear${NC}"
echo -e "5. Set permissions: ${GREEN}chown -R www-data:www-data storage bootstrap/cache${NC}"
echo ""
