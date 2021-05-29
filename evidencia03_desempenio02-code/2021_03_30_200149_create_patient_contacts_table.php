<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')
                ->unsigned();
            $table->string('name', 45)
                ->nullable();
            $table->enum('relationship', ['Caregiver', 'Father', 'Mother', 
                'Son', 'Daughter', 'Brother', 'Sister', 'Friend', 'Other'
            ]);
            $table->string('phone1', 14)
                ->nullable();
            $table->string('phone2', 14)
                ->nullable();
            $table->string('email', 60)
                ->nullable();
            $table->enum('emergency_contact', ['No', 'Yes'])
                ->default('Yes');
            $table->enum('financially_responsible', ['No', 'Yes'])
                ->default('No');
            $table->text('notes')
                ->nullable();    
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('updated_by')
                ->nullable()
                ->default(0);
        });

        Schema::table('patient_contacts', function($table) {
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
        Schema::dropIfExists('patient_contacts');
    }
}
