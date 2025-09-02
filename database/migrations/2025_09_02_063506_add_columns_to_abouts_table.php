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
        Schema::table('abouts', function (Blueprint $table) {
            $table->string('logo_image')->nullable()->after('id');
            $table->string('poster_image')->nullable()->after('logo_image');
            $table->text('our_story')->nullable()->after('poster_image');
            $table->text('our_vision')->nullable()->after('our_story');
            $table->text('our_mission')->nullable()->after('our_vision');
            $table->text('the_company')->nullable()->after('our_mission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->dropColumn([
                'logo_image',
                'poster_image',
                'our_story',
                'our_vision',
                'our_mission',
                'the_company',
            ]);
        });
    }
};
