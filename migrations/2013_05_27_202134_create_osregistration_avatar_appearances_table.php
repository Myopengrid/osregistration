<?php

class Osregistration_Create_Osregistration_Avatar_Appearances_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('osregistration_avatar_appearances', function($table)
        {
            $table->increments('id');
            $table->string('avatar_id');
            $table->string('name');
            $table->string('value');
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
        Schema::drop('osregistration_avatar_appearances');
    }
}