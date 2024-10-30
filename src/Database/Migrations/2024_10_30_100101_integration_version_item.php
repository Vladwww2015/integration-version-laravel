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
        if(!Schema::hasTable($table)) {
            Schema::create($table, function (Blueprint $table) {

                $table->increments('id');
                $table->string('identity_value')->nullable(false);
                $table->string('version_hash')->nullable();
                $table->string('checksum')->nullable();
                $table->string('status')->default(\IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersionItem::STATUS_SUCCESS);

                $table->integer('parent_id')->unsigned();
                $table->foreign('parent_id')
                    ->references('id')
                    ->on(\IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersion::TABLE)
                    ->onDelete('cascade');

                $table->index(['parent_id']);
                $table->index(['identity_value']);
                $table->index(['version_hash']);
                $table->unique(['parent_id', 'identity_value']);

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table = \IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersionItem::TABLE;

        Schema::dropIfExists($table);
    }
};
