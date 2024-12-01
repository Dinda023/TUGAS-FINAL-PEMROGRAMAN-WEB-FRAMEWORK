<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationColumnsToEventsTable extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->decimal('location_lat', 10, 7)->nullable()->after('location'); // Kolom latitude
            $table->decimal('location_lng', 10, 7)->nullable()->after('location_lat'); // Kolom longitude
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['location_lat', 'location_lng']);
        });
    }
}
