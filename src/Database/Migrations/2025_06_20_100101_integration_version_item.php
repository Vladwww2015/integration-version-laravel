<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        $table = \IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersionItem::TABLE;

        Schema::table($table, function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
        });
    }

    public function down(): void
    {
        $table = \IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersionItem::TABLE;

        Schema::table($table, function (Blueprint $table) {
            $table->unsignedInteger('id', true)->change();
        });
    }
};
