<?php

class Osregistration_Create_Osregistration_Avatar_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('osregistration_avatar', function($table)
        {
            $table->increments('id');
            $table->string('uuid');
            $table->string('name');
            $table->string('slug');
            $table->string('description');
            $table->string('image_name');
            $table->integer('order')->default(999);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('osregistration_avatar');
    }
}