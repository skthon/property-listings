<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_listings', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('type');
            $table->text('description');
            $table->bigInteger('price')->index();
            $table->integer('property_type_id')->nullable();
            $table->integer('num_bedrooms')->default(0)->index();
            $table->integer('num_bathrooms')->default(0);
            $table->string('image_full');
            $table->string('image_thumbnail');

            $table->string('county');
            $table->string('country');
            $table->string('town');
            $table->string('address');
            $table->double('latitude', 10, 8)->nullable();
            $table->double('longitude', 11, 8)->nullable();

            $table->timestamps();

            $table->foreign('property_type_id')
                ->references('id')->on('property_types')
                ->onDelete('SET NULL')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_listings');
    }
};
