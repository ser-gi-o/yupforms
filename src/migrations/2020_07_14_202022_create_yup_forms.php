<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYupForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yup_forms', function (Blueprint $table) {
            $table->increments('id');
            //$table->integer('owner_id')->default(0)->index();  //for multi-tenant use
            $table->integer('public_id')->index();
            $table->smallInteger('status')->default(0)->index();
            $table->integer('submissions')->default(0);
            $table->string('name')->index();
            $table->text('description')->nullable();

            $table->string('host')->nullable();
            $table->string('recaptcha')->nullable();

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
        Schema::dropIfExists('yup_forms');
    }
}
