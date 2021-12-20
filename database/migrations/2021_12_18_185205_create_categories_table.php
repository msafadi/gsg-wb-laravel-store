<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            // id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY
            // $table->bigInteger('id')->unsigned()->autoIncrement();
            // $table->unsignedBigInteger('id')->autoIncrement();
            // $table->bigIncrements('id');
            $table->id();

            // $table->unsignedBigInteger('parent_id')->nullable();
            // $table->foreign('parent_id')
            //     ->references('id')
            //     ->on('categories')
            //     ->onDelete('set null')
            //     ->onUpdate('set null');
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories', 'id')
                ->nullOnDelete()
                ->nullOnUpdate();

            // name VARCHAR(255) NOT NULL
            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();
            $table->string('image', 500)->nullable();
            
            // created_at TIMESTAMP
            // updated_at TIMESTAMP
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
