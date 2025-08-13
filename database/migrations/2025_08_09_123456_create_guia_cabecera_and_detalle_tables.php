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
        Schema::create('guia_cabecera', function (Blueprint $table) {
            $table->id();
            $table->string('numero_guia', 20)->unique();
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento')->nullable();
            
            // Información del remitente
            $table->string('remitente_nombre', 255);
            $table->string('remitente_documento', 20);
            $table->string('remitente_direccion', 255);
            $table->string('remitente_telefono', 15)->nullable();
            $table->string('remitente_email', 100)->nullable();
            
            // Información del destinatario
            $table->string('destinatario_nombre', 255);
            $table->string('destinatario_documento', 20);
            $table->string('destinatario_direccion', 255);
            $table->string('destinatario_telefono', 15)->nullable();
            $table->string('destinatario_email', 100)->nullable();
            
            // Información de origen y destino
            $table->string('origen_departamento', 100);
            $table->string('origen_provincia', 100);
            $table->string('origen_distrito', 100);
            $table->string('destino_departamento', 100);
            $table->string('destino_provincia', 100);
            $table->string('destino_distrito', 100);
            
            // Información del transportista
            $table->string('transportista_nombre', 255)->nullable();
            $table->string('transportista_ruc', 11)->nullable();
            $table->string('conductor_nombre', 255)->nullable();
            $table->string('conductor_licencia', 20)->nullable();
            $table->string('vehiculo_placa', 10)->nullable();
            
            // Información comercial
            $table->decimal('peso_total', 10, 3)->default(0);
            $table->integer('cantidad_bultos')->default(0);
            $table->decimal('valor_mercancia', 12, 2)->default(0);
            $table->decimal('flete', 10, 2)->default(0);
            $table->string('modalidad_traslado', 50)->default('PUBLICO'); // PUBLICO, PRIVADO
            $table->string('motivo_traslado', 100);
            
            // Estados y observaciones
            $table->enum('estado', ['PENDIENTE', 'EN_TRANSITO', 'ENTREGADO', 'ANULADO'])
                  ->default('PENDIENTE');
            $table->text('observaciones')->nullable();
            
            // Campos de auditoría
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('numero_guia');
            $table->index('fecha_emision');
            $table->index('estado');
            $table->index(['remitente_documento', 'destinatario_documento']);
            
            // Claves foráneas (opcional, ajustar según tu estructura)
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
        
        // Crear tabla guia_detalle
        Schema::create('guia_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guia_cabecera_id');
            $table->integer('item')->default(1);
            
            // Información del producto/mercancía
            $table->string('codigo_producto', 50)->nullable();
            $table->string('descripcion', 255);
            $table->string('unidad_medida', 20)->default('UND'); // UND, KG, M, etc.
            $table->decimal('cantidad', 12, 3);
            $table->decimal('peso_unitario', 10, 3)->default(0);
            $table->decimal('peso_total', 12, 3)->default(0);
            
            // Información comercial del detalle
            $table->decimal('valor_unitario', 12, 2)->default(0);
            $table->decimal('valor_total', 12, 2)->default(0);
            
            // Información adicional
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 100)->nullable();
            $table->string('serie', 100)->nullable();
            $table->text('observaciones')->nullable();
            
            // Estado del item
            $table->enum('estado_item', ['PENDIENTE', 'CARGADO', 'EN_TRANSITO', 'ENTREGADO', 'AVERIADO'])
                  ->default('PENDIENTE');
            
            // Campos de auditoría
            $table->timestamps();
            
            // Índices
            $table->index('guia_cabecera_id');
            $table->index('codigo_producto');
            $table->index('estado_item');
            $table->index(['guia_cabecera_id', 'item']);
            
            // Clave foránea
            $table->foreign('guia_cabecera_id')
                  ->references('id')
                  ->on('guia_cabecera')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guia_detalle');
        Schema::dropIfExists('guia_cabecera');
    }
};