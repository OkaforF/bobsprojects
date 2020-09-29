<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('client');
            $table->string('email');
            $table->string('phone');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });

        DB::table('projects')->insert([
            [
                'title'=> 'Ikea app',
                'client'=> 'Ikea',
                'email'=> 'a@a.com',
                'phone'=> '22232411',
            ],
            [
                'title'=> 'Netto website',
                'client'=> 'Netto',
                'email'=> 'fiona@gmail.com',
                'phone'=> '17263522',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
