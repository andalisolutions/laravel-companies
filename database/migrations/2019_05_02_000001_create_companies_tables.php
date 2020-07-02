<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTables extends Migration
{
    public function up()
    {
        Schema::create(Config::get('companies.tables.companies'), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id')->index();
            $table->string('name');
            $table->string('vat_number');
            $table->string('address');
            $table->boolean('tax_payer');
            $table->timestamps();

            $table
                ->foreign('owner_id')
                ->references('id')
                ->on(Config::get('companies.tables.users'))
                ->onDelete('cascade');
        });

        Schema::create(Config::get('companies.tables.members'), function (Blueprint $table) {
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
            $table->string('role');
            $table->json('permissions');

            $table->unique(['company_id', 'user_id']);

            $table
                ->foreign('company_id')
                ->references('id')
                ->on(Config::get('companies.tables.companies'))
                ->onDelete('cascade');

            $table
                ->foreign('user_id')
                ->references('id')
                ->on(Config::get('companies.tables.users'))
                ->onDelete('cascade');
        });

        Schema::create(Config::get('companies.tables.invitations'), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('email');
            $table->string('role');
            $table->json('permissions');
            $table->uuid('accept_token')->unique();
            $table->uuid('reject_token')->unique();
            $table->timestamps();

            $table
                ->foreign('company_id')
                ->references('id')
                ->on(Config::get('companies.tables.companies'))
                ->onDelete('cascade');

            $table
                ->foreign('user_id')
                ->references('id')
                ->on(Config::get('companies.tables.users'))
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop(Config::get('companies.tables.invitations'));
        Schema::drop(Config::get('companies.tables.members'));
        Schema::drop(Config::get('companies.tables.companies'));
    }
}
