<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });

        $sales = DB::table('sales')->get();

        foreach ($sales as $sale) {
            if (! $sale->menu_id || ! $sale->quantity) {
                continue;
            }

            DB::table('sale_lines')->insert([
                'sale_id' => $sale->id,
                'menu_id' => $sale->menu_id,
                'quantity' => $sale->quantity,
                'unit_price' => $sale->unit_price,
                'total' => $sale->total,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->updated_at,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_lines');
    }
};