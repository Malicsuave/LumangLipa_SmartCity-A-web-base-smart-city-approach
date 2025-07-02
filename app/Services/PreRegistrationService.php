<?php

namespace App\Services;

use App\Models\PreRegistration;
use App\Models\Resident;
use App\Models\SeniorCitizen;
use App\Notifications\PreRegistrationApproved;
use App\Notifications\PreRegistrationRejected;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class PreRegistrationService
{
    /**
     * Approve a pre-registration and create resident record
     */
    public function approve(PreRegistration $preRegistration): array
    {
        Log::info('PreRegistrationService: Starting approval process', [
            'registration_id' => $preRegistration->id,
            'current_status' => $preRegistration->status,
            'user_id' => auth()->id()
        ]);

        if ($preRegistration->status !== 'pending') {
            return [
                'success' => false,
                'message' => 'This registration has already been processed.'
            ];
        }

        try {
            return DB::transaction(function () use ($preRegistration) {
                // Update pre-registration status
                $preRegistration->update([
                    'status' => 'approved',
                    'processed_by' => auth()->id(),
                    'processed_at' => now()
                ]);

                // Create resident record
                $resident = $this->createResidentFromPreRegistration($preRegistration);

                // Handle senior citizen registration if applicable
                if ($preRegistration->is_senior_citizen) {
                    $this->createSeniorCitizenRecord($resident, $preRegistration);
                }

                // Send approval notification
                $this->sendApprovalNotification($preRegistration, $resident);

                Log::info('PreRegistrationService: Approval completed successfully', [
                    'registration_id' => $preRegistration->id,
                    'resident_id' => $resident->id
                ]);

                return [
                    'success' => true,
                    'message' => 'Pre-registration approved successfully.',
                    'resident' => $resident
                ];
            });
        } catch (Exception $e) {
            Log::error('PreRegistrationService: Approval failed', [
                'registration_id' => $preRegistration->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to approve registration. Please try again.'
            ];
        }
    }

    /**
     * Reject a pre-registration
     */
    public function reject(PreRegistration $preRegistration, string $reason): array
    {
        if ($preRegistration->status !== 'pending') {
            return [
                'success' => false,
                'message' => 'This registration has already been processed.'
            ];
        }

        try {
            $preRegistration->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'processed_by' => auth()->id(),
                'processed_at' => now()
            ]);

            // Send rejection notification
            if ($preRegistration->email) {
                $preRegistration->notify(new PreRegistrationRejected($reason));
            }

            Log::info('PreRegistrationService: Registration rejected', [
                'registration_id' => $preRegistration->id,
                'reason' => $reason
            ]);

            return [
                'success' => true,
                'message' => 'Pre-registration rejected successfully.'
            ];
        } catch (Exception $e) {
            Log::error('PreRegistrationService: Rejection failed', [
                'registration_id' => $preRegistration->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to reject registration. Please try again.'
            ];
        }
    }

    /**
     * Create resident record from pre-registration data
     */
    private function createResidentFromPreRegistration(PreRegistration $preRegistration): Resident
    {
        $residentData = [
            'barangay_id' => $this->generateBarangayId(),
            'first_name' => $preRegistration->first_name,
            'middle_name' => $preRegistration->middle_name,
            'last_name' => $preRegistration->last_name,
            'suffix' => $preRegistration->suffix,
            'sex' => $preRegistration->sex,
            'birth_date' => $preRegistration->birth_date,
            'birth_place' => $preRegistration->birth_place,
            'civil_status' => $preRegistration->civil_status,
            'citizenship' => $preRegistration->citizenship,
            'religion' => $preRegistration->religion,
            'occupation' => $preRegistration->occupation,
            'monthly_income' => $preRegistration->monthly_income,
            'educational_attainment' => $preRegistration->educational_attainment,
            'contact_number' => $preRegistration->contact_number,
            'email' => $preRegistration->email,
            'address' => $preRegistration->address,
            'purok' => $preRegistration->purok,
            'voter_status' => $preRegistration->voter_status,
            'precinct_number' => $preRegistration->precinct_number,
            'emergency_contact_name' => $preRegistration->emergency_contact_name,
            'emergency_contact_relationship' => $preRegistration->emergency_contact_relationship,
            'emergency_contact_number' => $preRegistration->emergency_contact_number,
            'pre_registration_id' => $preRegistration->id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Handle photo upload if exists
        if ($preRegistration->photo_path) {
            $residentData['photo_path'] = $this->copyPhoto($preRegistration->photo_path);
        }

        return Resident::create($residentData);
    }

    /**
     * Create senior citizen record
     */
    private function createSeniorCitizenRecord(Resident $resident, PreRegistration $preRegistration): void
    {
        if (!$preRegistration->is_senior_citizen) {
            return;
        }

        SeniorCitizen::create([
            'resident_id' => $resident->id,
            'senior_citizen_id' => $this->generateSeniorCitizenId(),
            'pension_type' => $preRegistration->pension_type,
            'monthly_pension' => $preRegistration->monthly_pension,
            'medical_conditions' => $preRegistration->medical_conditions,
            'medications' => $preRegistration->medications,
            'guardian_name' => $preRegistration->guardian_name,
            'guardian_contact' => $preRegistration->guardian_contact,
            'guardian_relationship' => $preRegistration->guardian_relationship,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Send approval notification
     */
    private function sendApprovalNotification(PreRegistration $preRegistration, Resident $resident): void
    {
        if ($preRegistration->email) {
            try {
                $preRegistration->notify(new PreRegistrationApproved($resident));
            } catch (Exception $e) {
                Log::warning('Failed to send approval notification', [
                    'registration_id' => $preRegistration->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Generate unique barangay ID
     */
    private function generateBarangayId(): string
    {
        do {
            $id = 'BRG-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (Resident::where('barangay_id', $id)->exists());

        return $id;
    }

    /**
     * Generate unique senior citizen ID
     */
    private function generateSeniorCitizenId(): string
    {
        do {
            $id = 'SC-' . date('Y') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (SeniorCitizen::where('senior_citizen_id', $id)->exists());

        return $id;
    }

    /**
     * Copy photo from pre-registration to resident folder
     */
    private function copyPhoto(string $originalPath): string
    {
        if (!Storage::disk('public')->exists($originalPath)) {
            return null;
        }

        $filename = basename($originalPath);
        $newPath = 'residents/photos/' . $filename;

        Storage::disk('public')->copy($originalPath, $newPath);
        
        return $newPath;
    }
}