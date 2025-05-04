<?php declare(strict_types=1);

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
        Schema::create('nodes', function (Blueprint $table) {
            $table->id();
            $table->uuid('node_uuid')->unique();
            $table->string('name');
            $table->string('type')->nullable();
            $table->boolean('online')->default(false);
            $table->timestamp('last_seen_at')->nullable();
            $table->string('fw_version')->nullable();
            $table->string('mqtt_username')->nullable();
            $table->string('mqtt_password')->nullable();
            $table->string('mqtt_broker')->nullable()->default('mqtt.my-cloud.com');
            $table->unsignedSmallInteger('mqtt_port')->nullable()->default(8883);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nodes');
    }
};
