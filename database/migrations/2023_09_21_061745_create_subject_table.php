<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectTable extends Migration
{
  public function up()
  {
    Schema::create('subject', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->foreign('user_id')->references('id')->on('users');
      $table->string('subject');
      $table->integer('pass_mark');
      $table->integer('mark_obtained')->nullable()->default(0);
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('subject');
  }
}

