<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRexesTable extends Migration
{
    public function up()
    {
        Schema::create('rexes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rexer_id')->constrained('users')->onDelete('cascade'); // The one doing the rex
            $table->foreignId('rexed_id')->constrained('users')->onDelete('cascade'); // The one being rex'ed
            $table->timestamps();

            $table->unique(['rexer_id', 'rexed_id']); // Prevent duplicate rex
        });
    }

    public function down()
    {
        Schema::dropIfExists('rexes');
    }
}
