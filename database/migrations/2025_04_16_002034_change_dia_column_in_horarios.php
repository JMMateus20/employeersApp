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
        Schema::table('horarios', function (Blueprint $table) {
            $table->dropColumn('dia');

            
            $table->unsignedBigInteger('dia_id')->after('id');

            $table->foreign('dia_id')->references('id')->on('dias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            $table->dropForeign(['dia_id']);
            $table->dropColumn('dia_id');

            $table->string('dia');
        });
    }
};
