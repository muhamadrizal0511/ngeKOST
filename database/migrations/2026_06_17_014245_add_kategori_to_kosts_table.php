<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kost', function (Blueprint $table) {
    $table->string('kategori')->default('Putra')->after('harga');
});
    }

    public function down(): void
    {
        Schema::table('kost', function (Blueprint $table) {
    $table->dropColumn('kategori');
});
    }
};