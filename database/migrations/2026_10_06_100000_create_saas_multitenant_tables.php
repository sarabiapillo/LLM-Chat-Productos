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
        // 1. Tabla de Empresas (Tenants)
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('logo_url')->nullable();
            $table->enum('plan', ['Básico', 'Plus', 'Premium'])->default('Básico');
            $table->enum('status', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });

        // 2. Agregar company_id a la tabla de usuarios
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'company_id')) {
                $table->foreignId('company_id')->nullable()->after('role')->constrained('companies')->nullOnDelete();
            }
        });

        // 3. Tabla de Categorías de Productos por Empresa
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Tabla de Productos por Empresa
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }
        });

        Schema::dropIfExists('companies');
    }
};
