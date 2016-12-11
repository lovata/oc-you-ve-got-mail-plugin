<?php namespace Lovata\YouVeGotMail\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLovataYouvegotmailMailings extends Migration
{
    public function up()
    {
        Schema::create('lovata_youvegotmail_mailings', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('template_id')->nullable();
            $table->string('data_source')->nullable();
            $table->integer('data_source_id')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('lovata_youvegotmail_mailings');
    }
}