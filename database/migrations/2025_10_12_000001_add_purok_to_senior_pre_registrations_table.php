<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('senior_pre_registrations', function (Blueprint $table) {
            $table->string('purok')->after('address');
            $table->string('custom_purok')->nullable()->after('purok');
        });
    }

    public function down(): void
    {
        Schema::table('senior_pre_registrations', function (Blueprint $table) {
            $table->dropColumn(['purok', 'custom_purok']);
        });
    }
};
