<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            // Update type_of_resident enum values
            $table->dropColumn('type_of_resident');
        });
        
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->enum('type_of_resident', ['Non-Migrant', 'Migrant', 'Transient'])->after('id');
        });
        
        Schema::table('pre_registrations', function (Blueprint $table) {
            // Update sex enum to include more options
            $table->dropColumn('sex');
        });
        
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->enum('sex', ['Male', 'Female', 'Non-binary', 'Transgender', 'Other'])->after('birthdate');
        });
        
        Schema::table('pre_registrations', function (Blueprint $table) {
            // Update citizenship_type enum values
            $table->dropColumn('citizenship_type');
        });
        
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->enum('citizenship_type', ['FILIPINO', 'DUAL', 'NATURALIZED', 'FOREIGN'])->after('civil_status');
        });
        
        Schema::table('pre_registrations', function (Blueprint $table) {
            // Add emergency contact fields if they don't exist
            if (!Schema::hasColumn('pre_registrations', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('address');
            }
            if (!Schema::hasColumn('pre_registrations', 'emergency_contact_relationship')) {
                $table->string('emergency_contact_relationship', 50)->nullable()->after('emergency_contact_name');
            }
            if (!Schema::hasColumn('pre_registrations', 'emergency_contact_number')) {
                $table->string('emergency_contact_number', 11)->nullable()->after('emergency_contact_relationship');
            }
            
            // Make email_address nullable since it's optional
            $table->string('email_address', 255)->nullable()->change();
            
            // Make monthly_income nullable
            $table->decimal('monthly_income', 10, 2)->nullable()->change();
            
            // Make profession_occupation nullable
            $table->string('profession_occupation', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            // Revert type_of_resident enum
            $table->dropColumn('type_of_resident');
        });
        
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->enum('type_of_resident', ['Permanent', 'Temporary', 'Boarder/Transient'])->after('id');
        });
        
        Schema::table('pre_registrations', function (Blueprint $table) {
            // Revert sex enum
            $table->dropColumn('sex');
        });
        
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->enum('sex', ['Male', 'Female'])->after('birthdate');
        });
        
        Schema::table('pre_registrations', function (Blueprint $table) {
            // Revert citizenship_type enum
            $table->dropColumn('citizenship_type');
        });
        
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->enum('citizenship_type', ['FILIPINO', 'Dual Citizen', 'Foreigner'])->after('civil_status');
        });
        
        Schema::table('pre_registrations', function (Blueprint $table) {
            // Remove emergency contact fields
            if (Schema::hasColumn('pre_registrations', 'emergency_contact_name')) {
                $table->dropColumn('emergency_contact_name');
            }
            if (Schema::hasColumn('pre_registrations', 'emergency_contact_relationship')) {
                $table->dropColumn('emergency_contact_relationship');
            }
            if (Schema::hasColumn('pre_registrations', 'emergency_contact_number')) {
                $table->dropColumn('emergency_contact_number');
            }
        });
    }
};
