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
        if(!Schema::hasTable($table)) {
            Schema::create($table, function (Blueprint $table) {
                $table->increments('id');
                $table->string('source')->unique();
                $table->string('identity_column')->nullable(false);
                $table->string('hash')->nullable();
                $table->string('status')->default(\IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersion::STATUS_PENDING);
                $table->string('table_name')->nullable();
                $table->json('checksum_columns')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table = \IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersion::TABLE;

        Schema::dropIfExists($table);
    }
};
