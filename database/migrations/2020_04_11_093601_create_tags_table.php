<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("tags", function (Blueprint $table) {

            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name', 255);
            $table->integer('profit_type'); //損(-1)資(0)益(1)
            $table->string('comment', 255)->nullable();
            $table->timestamps();


            //*********************************
            // Foreign KEY [ Uncomment if you want to use!! ]
            //*********************************
            $table->foreign("user_id")->references("id")->on("users");


            // ----------------------------------------------------
            // -- SELECT [tags]--
            // ----------------------------------------------------
            // $query = DB::table("tags")
            // ->leftJoin("users","users.id", "=", "tags.user_id")
            // ->get();
            // dd($query); //For checking


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("tags");
    }
}
