<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('violation_categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('negative_comments')->nullable();
            $table->string('intimidating_language')->nullable();
            $table->string('insults_based_on_personal_characteristics')->nullable();
            $table->string('threats_of_physical_violence')->nullable();
            $table->string('public_ridicule')->nullable();
            $table->string('shaming')->nullable();
            $table->string('unsolicited_sexual_advances')->nullable();
            $table->string('inappropriate_comments')->nullable();
            $table->string('direct_threats')->nullable();
            $table->string('offensive_language')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('violation_categories');
    }
};

