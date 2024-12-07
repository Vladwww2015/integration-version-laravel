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
        $table = \IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersion::TABLE;
        if(Schema::hasTable($table)) {
            Schema::table($table, function (Blueprint $table) {
                $table->timestamp('hash_date_time')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table = \IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersion::TABLE;

        Schema::dropColumns($table, 'hash_date_time');
    }
};
