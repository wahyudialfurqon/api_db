<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            // Kolom untuk menyimpan URL gambar dari Cloudinary
            $table->string('image_url');
            // Kolom untuk menyimpan Public ID gambar di Cloudinary
            $table->string('image_public_id');
            $table->string('item_name');
            $table->string('category');
            $table->text('item_description');
            $table->string('uploaded_by');
            $table->string('address');
            $table->string('phone_number');
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
        Schema::dropIfExists('items');
    }
}
