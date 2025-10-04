<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
        {
            Schema::table('batidas', function (Blueprint $table) {
                $table->text('observacao')->nullable()->after('tipo');
            });
        }

        public function down(): void
        {
            Schema::table('batidas', function (Blueprint $table) {
                $table->dropColumn('observacao');
            });
        }
};
