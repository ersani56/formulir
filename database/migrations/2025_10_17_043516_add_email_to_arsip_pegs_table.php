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
        Schema::table('arsip_pegs', function (Blueprint $table) {
            $table->string('email')->nullable()->after('nip');
        });
    }

    public function down()
    {
        Schema::table('arsip_pegs', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
