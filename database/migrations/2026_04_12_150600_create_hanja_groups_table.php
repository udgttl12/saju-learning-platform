<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('hanja_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_type', 30)->comment('category|collection|track');
            $table->string('code', 50);
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_core')->default(true);
            $table->timestamps();

            $table->unique(['group_type', 'code'], 'uq_hanja_groups_type_code');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('hanja_groups');

    }
};
