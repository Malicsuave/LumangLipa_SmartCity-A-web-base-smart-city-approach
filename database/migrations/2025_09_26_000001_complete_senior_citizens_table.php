<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, backup any existing data
        if (Schema::hasTable('senior_citizens')) {
            DB::statement('CREATE TABLE senior_citizens_backup AS SELECT * FROM senior_citizens');
        }
        
        // Drop the existing table and recreate with complete structure
        Schema::dropIfExists('senior_citizens');
        
        Schema::create('senior_citizens', function (Blueprint $table) {
            $table->id();
            
            // Step 1: Personal Information
            $table->enum('type_of_resident', ['Non-Migrant', 'Migrant', 'Transient'])->default('Non-Migrant');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('birthdate');
            $table->string('birthplace');
            $table->enum('sex', ['Male', 'Female', 'Non-binary', 'Transgender', 'Other']);
            $table->string('sex_details')->nullable();
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Divorced', 'Separated']);
            $table->enum('citizenship_type', ['FILIPINO', 'DUAL', 'NATURALIZED', 'FOREIGN']);
            $table->string('citizenship_country')->nullable();
            $table->string('educational_attainment')->nullable();
            $table->enum('education_status', ['Studying', 'Graduated', 'Stopped Schooling', 'Not Applicable'])->nullable();
            $table->string('religion')->nullable();
            $table->string('profession_occupation')->nullable();
            
            // Step 2: Contact Information  
            $table->string('contact_number', 11);
            $table->string('email_address')->nullable();
            $table->text('current_address');
            
            // Step 3: Photo and Signature
            $table->string('photo')->nullable();
            $table->string('signature')->nullable();
            
            // Step 4: Senior Citizen Specific Information
            // Health Information
            $table->enum('health_condition', ['excellent', 'good', 'fair', 'poor', 'critical'])->nullable();
            $table->enum('mobility_status', ['independent', 'assisted', 'wheelchair', 'bedridden'])->nullable();
            $table->text('medical_conditions')->nullable();
            
            // Emergency Contact Information (from step 2 and step 4)
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_number', 11);
            $table->enum('emergency_contact_relationship', [
                'spouse', 'child', 'sibling', 'parent', 'relative', 'friend', 'neighbor', 'caregiver'
            ]);
            $table->text('emergency_contact_address')->nullable();
            
            // Benefits and Pension Information
            $table->boolean('receiving_pension')->default(false);
            $table->enum('pension_type', [
                'SSS', 'GSIS', 'Government Employee', 'Private Company', 'Social Pension', 'Other'
            ])->nullable();
            $table->decimal('pension_amount', 10, 2)->nullable();
            
            // PhilHealth Information
            $table->boolean('has_philhealth')->default(false);
            $table->string('philhealth_number')->nullable();
            
            // Senior Citizen Discount Card
            $table->boolean('has_senior_discount_card')->default(false);
            
            // Services and Programs
            $table->json('services')->nullable();
            
            // Senior ID Management
            $table->string('senior_id_number')->unique()->nullable();
            $table->date('senior_id_issued_at')->nullable();
            $table->date('senior_id_expires_at')->nullable();
            $table->enum('senior_id_status', ['not_issued', 'issued', 'needs_renewal', 'expired'])->default('not_issued');
            
            // Additional fields
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('registered_by')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['last_name', 'first_name']);
            $table->index('birthdate');
            $table->index('senior_id_status');
            $table->index('receiving_pension');
            $table->index('has_philhealth');
            $table->index('has_senior_discount_card');
            $table->index('contact_number');
            $table->index('registered_by');
            
            // Foreign key constraint
            $table->foreign('registered_by')->references('id')->on('users')->onDelete('set null');
        });
        
        // Migrate existing data if any
        $this->migrateExistingData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore from backup if it exists
        if (Schema::hasTable('senior_citizens_backup')) {
            Schema::dropIfExists('senior_citizens');
            DB::statement('CREATE TABLE senior_citizens AS SELECT * FROM senior_citizens_backup');
            Schema::dropIfExists('senior_citizens_backup');
        } else {
            Schema::dropIfExists('senior_citizens');
        }
    }
    
    /**
     * Migrate existing data from backup to new structure
     */
    private function migrateExistingData(): void
    {
        if (!Schema::hasTable('senior_citizens_backup')) {
            return;
        }
        
        $backupData = DB::table('senior_citizens_backup')->get();
        
        foreach ($backupData as $record) {
            // Get resident data if exists
            $resident = DB::table('residents')->where('id', $record->resident_id)->first();
            
            DB::table('senior_citizens')->insert([
                'id' => $record->id,
                
                // Step 1: Personal Information (from resident if available)
                'type_of_resident' => $resident->type_of_resident ?? 'Non-Migrant',
                'first_name' => $resident->first_name ?? 'Unknown',
                'middle_name' => $resident->middle_name ?? null,
                'last_name' => $resident->last_name ?? 'Unknown',
                'suffix' => $resident->suffix ?? null,
                'birthdate' => $resident->birthdate ?? '1960-01-01',
                'birthplace' => $resident->birthplace ?? 'Unknown',
                'sex' => $resident->sex ?? 'Male',
                'sex_details' => $resident->sex_details ?? null,
                'civil_status' => $resident->civil_status ?? 'Single',
                'citizenship_type' => $resident->citizenship_type ?? 'FILIPINO',
                'citizenship_country' => $resident->citizenship_country ?? null,
                'educational_attainment' => $resident->educational_attainment ?? null,
                'education_status' => $resident->education_status ?? null,
                'religion' => $resident->religion ?? null,
                'profession_occupation' => $resident->profession_occupation ?? null,
                
                // Step 2: Contact Information
                'contact_number' => $resident->contact_number ?? '09000000000',
                'email_address' => $resident->email_address ?? null,
                'current_address' => $resident->address ?? 'Unknown',
                
                // Step 3: Photo and Signature
                'photo' => $resident->photo ?? null,
                'signature' => $resident->signature ?? null,
                
                // Step 4: Senior Citizen Specific Information
                'health_condition' => null, // New field
                'mobility_status' => null, // New field
                'medical_conditions' => $record->health_conditions ?? null,
                'emergency_contact_name' => $record->emergency_contact_name ?? null,
                'emergency_contact_number' => $record->emergency_contact_number ?? null,
                'emergency_contact_relationship' => $this->mapRelationship($record->emergency_contact_relationship ?? null),
                'emergency_contact_address' => null, // New field
                
                // Benefits mapping
                'receiving_pension' => $record->receiving_pension ?? false,
                'pension_type' => $this->mapPensionType($record->pension_type ?? null),
                'pension_amount' => $record->pension_amount ?? null,
                'has_philhealth' => $record->has_philhealth ?? false,
                'philhealth_number' => $record->philhealth_number ?? null,
                'has_senior_discount_card' => $record->has_senior_discount_card ?? false,
                
                // Programs mapping
                'services' => $record->programs_enrolled ?? null,
                
                // Senior ID Management
                'senior_id_number' => $record->senior_id_number ?? null,
                'senior_id_issued_at' => $record->senior_id_issued_at ?? null,
                'senior_id_expires_at' => $record->senior_id_expires_at ?? null,
                'senior_id_status' => $record->senior_id_status ?? 'not_issued',
                
                'notes' => $record->notes ?? null,
                'registered_by' => $resident->registered_by ?? null,
                'created_at' => $record->created_at ?? now(),
                'updated_at' => $record->updated_at ?? now(),
            ]);
        }
        
        // Drop the backup table after successful migration
        Schema::dropIfExists('senior_citizens_backup');
    }
    
    /**
     * Map old relationship values to new enum values
     */
    private function mapRelationship($oldValue): ?string
    {
        if (!$oldValue) return null;
        
        $mapping = [
            'other' => 'caregiver',
        ];
        
        return $mapping[strtolower($oldValue)] ?? (in_array(strtolower($oldValue), [
            'spouse', 'child', 'sibling', 'parent', 'relative', 'friend', 'neighbor', 'caregiver'
        ]) ? strtolower($oldValue) : 'caregiver');
    }
    
    /**
     * Map old pension type values to new enum values
     */
    private function mapPensionType($oldValue): ?string
    {
        if (!$oldValue) return null;
        
        return in_array($oldValue, [
            'SSS', 'GSIS', 'Government Employee', 'Private Company', 'Social Pension', 'Other'
        ]) ? $oldValue : 'Other';
    }
};
