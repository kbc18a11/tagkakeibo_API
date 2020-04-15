<?php
            use Illuminate\Support\Facades\Schema;
            use Illuminate\Database\Schema\Blueprint;
            use Illuminate\Database\Migrations\Migration;

            class CreateGoodTagsTable extends Migration
            {
                /**
                 * Run the migrations.
                 *
                 * @return void
                 */
                public function up()
                {
                    Schema::create("good_tags", function (Blueprint $table) {
						$table->increments('id');
						$table->bigInteger('tag_id')->unsigned();
						$table->bigInteger('user_id')->unsigned();
						$table->timestamps();

						$table->foreign("tag_id")->references("id")->on("tags");
						$table->foreign("user_id")->references("id")->on("users");



						// ----------------------------------------------------
						// -- SELECT [goodTags]--
						// ----------------------------------------------------
						// $query = DB::table("goodTags")
						// ->leftJoin("tag","tag.id", "=", "goodTags.tag_id")
						// ->leftJoin("users","users.id", "=", "goodTags.user_id")
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
                    Schema::dropIfExists("good_tags");

                }
            }
