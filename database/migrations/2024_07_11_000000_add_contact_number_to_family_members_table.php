<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->string('contact_number', 20)->nullable()->after('work');
        });
    }

    public function down()
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->dropColumn('contact_number');
        });
    }
}; 