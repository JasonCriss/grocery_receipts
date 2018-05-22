<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->text('raw');
            $table->date('transactiondate')->nullable();
            $table->time('transactiontime')->nullable();
            $table->string('cashier')->nullable();
            $table->string('invoicenum')->nullable();
            $table->string('transactionnum')->nullable();
            $table->float('netsales')->nullable();
            $table->float('tax')->nullable();
            $table->float('totalsales')->nullable();
            $table->integer('numitems')->nullable();
            $table->float('memberdiscount')->nullable();
            $table->float('totalsavings')->nullable();
            $table->boolean('processed')->default(false);
            $table->string('customer')->nullable();
            $table->string('store')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
