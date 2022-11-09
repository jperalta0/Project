<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number');
            $table->string('position');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('extname')->nullable();
            $table->string('address');
            $table->string('email');
            $table->date('birthdate');
            $table->integer('civilstatus');
            $table->integer('gender');
            //$table->tinyInteger('isActive');
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
        Schema::dropIfExists('employees');
    }
}
