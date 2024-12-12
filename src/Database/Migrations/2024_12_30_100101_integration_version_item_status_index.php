<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $table = \IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersionItem::TABLE;
        if(Schema::hasTable($table)) {
            Schema::table($table, function (Blueprint $table) {
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table = \IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersionItem::TABLE;
        if(Schema::hasTable($table) && Schema::hasIndex($table, 'status')) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropIndex('status');
            });
        }
    }
};
