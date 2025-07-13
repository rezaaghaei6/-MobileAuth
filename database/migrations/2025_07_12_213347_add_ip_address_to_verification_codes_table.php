<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('verification_codes', function (Blueprint $table) {
        $table->ipAddress('ip_address')->nullable()->after('expires_at');
    });
}

public function down()
{
    Schema::table('verification_codes', function (Blueprint $table) {
        $table->dropColumn('ip_address');
    });
}

};
