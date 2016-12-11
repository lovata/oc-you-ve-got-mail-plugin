<?php namespace Lovata\YouVeGotMail\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLovataYouvegotmailCategorySubscriber extends Migration
{
    public function up()
    {
        Schema::create('lovata_youvegotmail_category_subscriber', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('category_id')->unsigned();
            $table->integer('subscriber_id')->unsigned();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('lovata_youvegotmail_category_subscriber');
    }
}