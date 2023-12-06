<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class init extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Lumen table, required for DB job queue
         */
        Schema::create('users', function (Blueprint $table) {
            $table->increments('userID');
            $table->string('firstName', 100)->default('');
            $table->string('lastName', 100)->default('');
            $table->string('email', 100)->unique();
            $table->string('password', 200);
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->increments('leadID');
            $table->unsignedInteger('userID');
            $table->string('businessID', 100)->default('');
            $table->string('companyName', 100)->default('');
            $table->string('contactPerson', 100)->default('');
            $table->string('contactPhone', 100)->default('');
            $table->string('contactEmail', 100)->default('');
            $table->string('header', 100)->default('');
            $table->string('description', 1000)->default('');
            $table->timestamps();

            $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade');
        });

        Schema::create('salesAppointments', function (Blueprint $table) {
            $table->increments('salesAppointmentID');
            $table->unsignedInteger('userID');
            $table->unsignedInteger('leadID');
            $table->dateTime('timeStart')->nullable();
            $table->dateTime('timeEnd')->nullable();
            $table->string('notes', 1000)->default('');
            $table->timestamps();

            $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade');
            $table->foreign('leadID')->references('leadID')->on('leads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // First migration. No down migration.

    }
}
