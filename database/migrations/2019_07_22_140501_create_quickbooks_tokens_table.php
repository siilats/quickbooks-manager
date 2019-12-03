<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuickbooksTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName(), function(Blueprint $table) {
            $table->increments('id');
            $table->string('connection');
            $table->text('access_token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('realm_id')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->timestamp('refresh_at')->nullable();
            $table->timestamp('refresh_expire_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->getTableName());
    }

    protected function getTableName()
    {
        return config('quickbooks_manager.table_name');
    }
}
