<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adding the 'location' column to the 'events' table
        Schema::table('events', function (Blueprint $table) {
            $table->string('location')->nullable(); // Add location column as nullable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Removing the 'location' column from the 'events' table
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
}
