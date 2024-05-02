<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $categories = [
            'job',
            'marketplace',
            'entertainment',
            'food',
            'technology',
            'fashion',
            'health',
            'education',
            'sports',
            'travel',
            'finance',
            'music',
            'art',
            'automotive',
            'home',
            'gardening',
            'pets',
            'books',
            'beauty',
            'fitness',
            'electronics',
            'diy',
            'outdoors',
            'gaming',
            'photography',
            'design',
            'business',
            'cooking',
            'crafts',
            'history'
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
