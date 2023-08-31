<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scan_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scan_id');
            $table->string('sourceid')->nullable();
            $table->string('alertRef')->nullable();
            $table->string('a_id')->nullable();
            $table->string('other')->nullable();
            $table->string('method')->nullable();
            $table->string('evidence')->nullable();
            $table->string('pluginId')->nullable();
            $table->string('cweid')->nullable();
            $table->string('confidence')->nullable();
            $table->string('wascid')->nullable();
            $table->text('description')->nullable();
            $table->string('messageId')->nullable();
            $table->string('inputVector')->nullable();
            $table->string('url')->nullable();
            $table->json('tags')->nullable();
            $table->text('reference')->nullable();
            $table->text('solution')->nullable();
            $table->string('alert')->nullable();
            $table->string('param')->nullable();
            $table->string('attack')->nullable();
            $table->string('name')->nullable();
            $table->string('risk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scan_alerts');
    }
};
