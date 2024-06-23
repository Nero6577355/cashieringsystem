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
         Schema::table('foods', function (Blueprint $table) {
            $table->integer('number_of_items')->after('category_id')->nullable();
            // Replace 'column_name' with the name of the column after which you want to add the new column
            // You can also use other methods like ->default() or ->unsigned() if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->dropColumn('number_of_items');
        });
    }
};
