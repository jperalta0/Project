<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeGeneralInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_general_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->date('datehired');
            $table->integer('campus_id');
            $table->date('dateseparated')->nullable();
            $table->integer('term_id');
            $table->integer('status_id');
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
        Schema::dropIfExists('employee_general_infos');
    }
}
