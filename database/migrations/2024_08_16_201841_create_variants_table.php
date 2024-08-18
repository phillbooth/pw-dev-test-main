<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('variants', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key as UUID
            $table->uuid('sku_id'); // Foreign key to skus table
            $table->string('colours');
            $table->string('size');
            $table->string('variant')->unique(); // Unique index for variant
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');

            // Index for faster lookups
            $table->index('sku_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('variants');
    }
};
