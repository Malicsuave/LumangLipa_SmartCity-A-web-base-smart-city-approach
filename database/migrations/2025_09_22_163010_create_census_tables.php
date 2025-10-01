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
        // Create households table for census
        Schema::create('census_households', function (Blueprint $table) {
            $table->id('household_id');
            $table->string('head_name', 100);
            $table->string('address', 255);
            $table->string('contact_number', 20)->nullable();
            $table->string('housing_type', 50);
            $table->timestamps();
        });

        // Create census_members table
        Schema::create('census_members', function (Blueprint $table) {
            $table->id('member_id');
            $table->foreignId('household_id')->constrained('census_households', 'household_id')->onDelete('cascade');
            $table->string('fullname', 100);
            $table->string('relationship_to_head', 50);
            $table->date('dob');
            $table->string('gender', 10);
            $table->string('civil_status', 20);
            $table->string('education', 50)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('category', 50)->nullable(); // e.g. Senior, PWD, 4Ps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('census_members');
        Schema::dropIfExists('census_households');
    }
};
