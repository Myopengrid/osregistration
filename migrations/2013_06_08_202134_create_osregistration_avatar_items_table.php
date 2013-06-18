<?php

class Osregistration_Create_Osregistration_Avatar_Items_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('osregistration_avatar_items', function($table)
        {
            $uuid_zero = '00000000-0000-0000-0000-000000000000';

            $table->increments('id');
            $table->string('assetID', 36);
            $table->integer('assetType')->default(0);
            $table->string('inventoryName', 64)->default('');
            $table->string('inventoryDescription', 128)->default('');
            $table->integer('inventoryNextPermissions')->unsigned()->default(0);
            $table->integer('inventoryCurrentPermissions')->unsigned()->default(0);
            $table->integer('invType')->default(0);
            $table->string('creatorID', 36)->default($uuid_zero);
            $table->integer('inventoryBasePermissions')->unsigned()->default(0);
            $table->integer('inventoryEveryOnePermissions')->unsigned()->default(0);
            $table->integer('salePrice')->default(0);
            $table->boolean('saleType')->default(0);
            $table->integer('creationDate')->default(0);
            $table->string('groupID', 36)->default($uuid_zero);
            $table->boolean('groupOwned')->default(0);
            $table->integer('flags')->default(0);
            $table->string('inventoryID', 36)->default($uuid_zero);
            $table->string('avatarID', 36)->default($uuid_zero);
            $table->string('parentFolderID', 36)->default('');
            $table->integer('inventoryGroupPermissions')->unsigned()->default(0);
            $table->string('avatar_id');
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
        Schema::drop('osregistration_avatar_items');
    }
}