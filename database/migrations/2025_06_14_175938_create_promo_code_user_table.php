<?php

use App\Models\PromoCode;
use App\Models\User;
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
        Schema::create('promo_code_user', function (Blueprint $table) {

            $table->foreignIdFor(PromoCode::class)
                ->constrained('promo_codes')
                ->onDelete('cascade');

            $table->foreignIdFor(User::class)
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();

            $table->primary(['promo_code_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_code_user');
    }
};
