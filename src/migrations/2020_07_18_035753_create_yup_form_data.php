<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYupFormData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yup_form_data', function (Blueprint $table) {
            $table->id();
            $table->integer('form_id')->index();
            $table->smallInteger('flagged');
            $table->json('data');
            $table->json('server');
            $table->string('note', 512)->nullable();
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
        Schema::dropIfExists('yup_form_data');
    }
}
