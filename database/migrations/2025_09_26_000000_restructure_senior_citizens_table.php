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
        DB::statement('CREATE TABLE senior_citizens_backup AS SELECT * FROM senior_citizens');
        
        // Drop the existing table and recreate with correct structure
        Schema::dropIfExists('senior_citizens');
        
        Schema::create('senior_citizens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            
            // Senior ID Management
            $table->string('senior_id_number')->unique()->nullable();
            $table->date('senior_id_issued_at')->nullable();
            $table->date('senior_id_expires_at')->nullable();
            $table->enum('senior_id_status', ['not_issued', 'issued', 'needs_renewal', 'expired'])->default('not_issued');
            
            // Health Information (matching the form)
            $table->enum('health_condition', ['excellent', 'good', 'fair', 'poor', 'critical'])->nullable();
            $table->enum('mobility_status', ['independent', 'assisted', 'wheelchair', 'bedridden'])->nullable();
            $table->text('medical_conditions')->nullable(); // Replaces health_conditions
            
            // Emergency Contact Information
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->enum('emergency_contact_relationship', [
                'spouse', 'child', 'sibling', 'parent', 'relative', 'friend', 'neighbor', 'caregiver'
            ])->nullable();
            $table->text('emergency_contact_address')->nullable();
            
            // Benefits and Pension Information (matching the form)
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
            
            // Services and Programs (matching the form)
            $table->json('services')->nullable(); // Replaces programs_enrolled
            
            // Additional fields for future use
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('senior_id_status');
            $table->index('receiving_pension');
            $table->index('has_philhealth');
            $table->index('has_senior_discount_card');
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
            DB::table('senior_citizens')->insert([
                'id' => $record->id,
                'resident_id' => $record->resident_id,
                'senior_id_number' => $record->senior_id_number ?? null,
                'senior_id_issued_at' => $record->senior_id_issued_at ?? null,
                'senior_id_expires_at' => $record->senior_id_expires_at ?? null,
                'senior_id_status' => $record->senior_id_status ?? 'not_issued',
                
                // Map old health_conditions to new medical_conditions
                'medical_conditions' => $record->health_conditions ?? null,
                'health_condition' => null, // Will need to be set manually
                'mobility_status' => null, // New field
                
                // Emergency contact mapping
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
                
                'notes' => $record->notes ?? null,
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
            // Add more mappings as needed
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
