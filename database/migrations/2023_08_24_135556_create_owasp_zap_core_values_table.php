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
        Schema::create('owasp_zap_core_values', function (Blueprint $table) {
            $table->id();
            $table->string('alert_name')->nullable();
            $table->string('alert_status')->nullable();
            $table->string('alert_risk')->nullable();
            $table->string('alert_type')->nullable();
            $table->string('avg')->nullable();
            $table->string('owasp_score')->nullable();
            $table->string('detectability')->nullable();
            $table->string('exploitability')->nullable();
            $table->string('technical_impact')->nullable();
            $table->string('risk_severity')->nullable();
            $table->string('mitigation')->nullable();
            $table->string('time')->nullable();
            $table->string('health')->nullable();
            $table->string('s1')->nullable();
            $table->string('s2')->nullable();
            $table->string('s3')->nullable();
            $table->string('s4')->nullable();
            $table->string('s5')->nullable();
            $table->string('s6')->nullable();
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
        Schema::dropIfExists('owasp_zap_core_values');
    }
};
