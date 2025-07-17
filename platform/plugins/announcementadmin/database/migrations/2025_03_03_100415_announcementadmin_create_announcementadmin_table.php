<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('announcementadmins')) {
            Schema::create('announcementadmins', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255);
                $table->string('status', 60)->default('published');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('announcementadmins_translations')) {
            Schema::create('announcementadmins_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->foreignId('announcementadmins_id');
                $table->string('name', 255)->nullable();

                $table->primary(['lang_code', 'announcementadmins_id'], 'announcementadmins_translations_primary');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('announcementadmins');
        Schema::dropIfExists('announcementadmins_translations');
    }
};
