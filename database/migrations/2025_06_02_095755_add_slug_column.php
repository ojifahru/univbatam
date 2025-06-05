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
        Schema::table('about_us', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('key');
        });

        Schema::table('departements', function (Blueprint $table) {
            $table->json('slug')
                ->nullable()
                ->after('name')
                ->comment('JSON column to store slugs for different languages');
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        Schema::table('faculties', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title')->comment('Slug for the news article');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('departements', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('faculties', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
