<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppiontmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appiontments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('lead_id');
            $table->text('address');
            $table->string('appiontment_datetime');
            $table->longText('message');
            $table->longText('note')->nullable();
            $table->string('visiting_output')->nullable();
            $table->string('visitor')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('appiontments');
    }
}
