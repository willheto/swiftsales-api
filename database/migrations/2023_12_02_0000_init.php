<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->string('firstName', 100);
            $table->string('lastName', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 200);
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->increments('leadID');
            $table->unsignedInteger('userID');
            $table->string('businessID', 100);
            $table->string('companyName', 100);
            $table->string('contactPerson', 100);
            $table->string('contactPhone', 100);
            $table->string('contactEmail', 100);
            $table->string('header', 100);
            $table->string('description', 1000);
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
        // First migration. No down migration.

    }
}
