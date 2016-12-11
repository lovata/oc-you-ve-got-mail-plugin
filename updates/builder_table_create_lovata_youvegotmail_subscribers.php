<?php namespace Lovata\YouVeGotMail\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLovataYouvegotmailSubscribers extends Migration
{
    public function up()
    {
        Schema::create('lovata_youvegotmail_subscribers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('email');
            $table->string('name');
            $table->string('surname');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('lovata_youvegotmail_subscribers');
    }
}