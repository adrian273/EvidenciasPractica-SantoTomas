<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientInsurancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_insurances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')
                ->unsigned();
            $table->enum('insurance_type', ['Medicare', 'Medicaid', 'Private', 'HMO', 'Other']);
            $table->enum('primary_secondary', ['Primary', 'Secondary', 'Tertiary', 'Other']);
            $table->string('insurance_id', 20);
            $table->integer('sort_sequence')
                ->nullable();
            $table->string('name', 60)
                ->nullable();
            $table->date('effective_date')
                ->nullable();
            $table->date('thru_date')
                ->nullable();
            $table->text('notes')
                ->nullable();
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('updated_by')
                ->default(0)
                ->nullable();
        });

        Schema::table('patient_insurances', function($table) {
            $table->foreign('patient_id')->references('id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_insurances');
    }
}
