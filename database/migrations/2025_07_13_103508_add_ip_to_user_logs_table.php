<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('user_logs', function (Illuminate\Database\Schema\Blueprint $table) {
        $table->ipAddress('ip')->nullable()->after('message');
    });
}

public function down()
{
    Schema::table('user_logs', function (Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn('ip');
    });
}

};
