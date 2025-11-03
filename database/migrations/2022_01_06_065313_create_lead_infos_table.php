<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
			$table->integer('assigned_to')->nullable();
            $table->integer('batch')->nullable();
            $table->string('lead_created_date')->nullable();
            $table->string('name')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->text('lead_query')->nullable();
            $table->text('inbox_url')->nullable();
            $table->string('source')->nullable();
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('lead_infos');
    }
}
