<?php namespace Lovata\YouVeGotMail\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLovataYouvegotmailCategorySubscriber extends Migration
{
    public function up()
    {
        Schema::create('lovata_youvegotmail_link', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->integer('category_id')->unsigned();
            $table->integer('subscriber_id')->unsigned();
            $table->primary(['category_id', 'subscriber_id']);
            
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('lovata_youvegotmail_category_subscriber');
    }
}