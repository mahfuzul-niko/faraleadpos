<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable();
            $table->integer('file_no')->nullable();
            $table->integer('saller_id')->nullable();
            $table->date('sale_date')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('bill_type')->nullable()->comment('Monthly/Yearly');
            $table->integer('installation_charge')->nullable();
            $table->date('installation_date')->nullable();
            $table->integer('installer_id')->nullable();
            $table->integer('advance')->nullable();
            $table->integer('due')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
