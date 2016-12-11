<?php namespace Lovata\YouVeGotMail\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLovataYouvegotmailCategories extends Migration
{
    public function up()
    {
        Schema::create('lovata_youvegotmail_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('lovata_youvegotmail_categories');
    }
}