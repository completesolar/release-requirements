<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('requirements', function (Blueprint $table) {
            $table->string('stage');
            $table->string('name');

            $table->unique(['stage', 'name']);
        });
    }

    public function down(): void
    {
        Schema::drop('requirements');
    }
};
