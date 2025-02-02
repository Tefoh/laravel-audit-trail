<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $connection = Config::get('audit-trail.database.connection', Config::get('database.default'));
        $tableName = Config::get('audit-trail.database.table_name', 'audit_logs');

        Schema::connection($connection)->create($tableName, function (Blueprint $table) {
            $table->id();
            $table->string('table_name')->nullable();
            $table->string('operation_type');
            $table->text('url')->nullable();
            $table->text('query')->nullable();
            $table->text('bindings')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->decimal('time', 11)->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = Config::get('audit-trail.database.connection', Config::get('database.default'));
        $tableName = Config::get('audit-trail.database.table_name', 'audit_logs');

        Schema::connection($connection)->dropIfExists($tableName);
    }
};
