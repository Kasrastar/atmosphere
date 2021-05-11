<?php


namespace Bot\Database\Schemas;


use BotFramework\Database\Schemas\TableSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

class MessageSchema extends TableSchema
{
	protected $tableName = 'messages';

	public function up ()
	{
		Capsule::schema()->create($this->tableName, function (Blueprint $table) {
			$table->id();
			$table->string('from');
			$table->string('message');
			$table->timestamps();
		});
	}
}
