<?php

require_once __DIR__ . '/vendor/autoload.php';


use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Exception\RuntimeException as RuntimeException;

class Make extends Command
{
	protected static $defaultName = 'make';

	protected function configure ()
	{
		$this->setDescription('make a new component');
		$this->addUsage(':<component> <arguments>');
	}

	protected function execute (InputInterface $input, OutputInterface $output) : int
	{
		throw new RuntimeException('See help');
	}
}

class MakeMiddleware extends Command
{
	protected static $defaultName = 'make:middleware';

	protected function configure ()
	{
		$this->setDescription('make a new middleware');
		$this->addArgument('names', InputArgument::IS_ARRAY, 'name or names (space separated)');
		$this->addUsage('Foo');
		$this->addUsage('Foo Bar');
	}

	protected function execute (InputInterface $input, OutputInterface $output) : int
	{
		$middlewares = $input->getArgument('names');

		if (empty($middlewares))
			throw new RuntimeException('Error. No name/names are entered.');

		foreach ($middlewares as $middleware)
		{
			$this->create_middleware_class($middleware);
			$this->put_in_provider($middleware);
		}

		$io = new SymfonyStyle($input, $output);
		$io->success('Done.');

		return Command::SUCCESS;
	}

	private function create_middleware_class ($name)
	{
		file_put_contents(__DIR__ . "/Middlewares/$name.php", "<?php


namespace Bot\Middlewares;


use BotFramework\Middlewares\Middleware;
use Longman\TelegramBot\Entities\Update;

class $name extends Middleware
{
	public function allow (Update \$update) : bool
	{
		// handle
	}
}
");
	}

	private function put_in_provider ($name)
	{
		$content = file_get_contents(__DIR__ . '/Providers/MiddlewareProvider.php');
		$where_to_insert = strpos($content, ']') - 1;
		$what_to_insert = "\t\t\\Bot\\Middlewares\\$name::class,\n\t";
		$content = substr_replace($content, $what_to_insert, $where_to_insert, 0);
		file_put_contents(__DIR__ . '/Providers/MiddlewareProvider.php', $content);
	}
}

class MakeScenario extends Command
{
	protected static $defaultName = 'make:scenario';

	protected function configure ()
	{
		$this->setDescription('make a new scenario');
		$this->addArgument('names', InputArgument::IS_ARRAY, 'name or names (space separated)');
		$this->addUsage('Foo');
		$this->addUsage('Foo Bar');
	}

	protected function execute (InputInterface $input, OutputInterface $output) : int
	{
		$scenarios = $input->getArgument('names');

		if (empty($scenarios))
			throw new RuntimeException('Error. No name/names are entered.');

		foreach ($scenarios as $scenario)
		{
			$this->create_scenario_class($scenario);
			$this->put_in_provider($scenario);
		}

		$io = new SymfonyStyle($input, $output);
		$io->success('Done.');

		return Command::SUCCESS;
	}

	private function create_scenario_class ($name)
	{
		file_put_contents(__DIR__ . "/Scenarios/$name.php", "<?php


namespace Bot\Scenarios;


use BotFramework\Scenarios\Scenario;
use Longman\TelegramBot\Entities\Update;

class $name extends Scenario
{
	protected \$conditions = [
		// condition classes here
	];

	protected function handle (Update \$update)
	{
		// handle
	}
}
");
	}

	private function put_in_provider ($name)
	{
		$content = file_get_contents(__DIR__ . '/Providers/ScenarioProvider.php');
		$where_to_insert = strpos($content, ']') - 1;
		$what_to_insert = "\t\t\\Bot\\Scenarios\\$name::class,\n\t";
		$content = substr_replace($content, $what_to_insert, $where_to_insert, 0);
		file_put_contents(__DIR__ . '/Providers/ScenarioProvider.php', $content);
	}
}

class MakeSubScenario extends Command
{
	protected static $defaultName = 'make:sub-scenario';

	protected function configure ()
	{
		$this->setDescription('make a new sub scenario');
		$this->addArgument('names', InputArgument::IS_ARRAY, 'name or names (space separated)');
		$this->addUsage('Foo');
		$this->addUsage('Foo Bar');
	}

	protected function execute (InputInterface $input, OutputInterface $output) : int
	{
		$sub_scenarios = $input->getArgument('names');

		if (empty($sub_scenarios))
			throw new RuntimeException('Error. No name/names are entered.');

		foreach ($sub_scenarios as $sub_scenario)
		{
			$this->create_sub_scenario_class($sub_scenario);
		}

		$io = new SymfonyStyle($input, $output);
		$io->success('Done.');

		return Command::SUCCESS;
	}

	private function create_sub_scenario_class ($name)
	{
		file_put_contents(__DIR__ . "/Scenarios/SubScenarios/$name.php", "<?php


namespace Bot\Scenarios\SubScenarios;


use BotFramework\Scenarios\SubScenarios\SubScenario;
use Longman\TelegramBot\Entities\Update;

class $name extends SubScenario
{
	protected function handle (Update \$update)
	{
		// handle
	}
}");
	}
}

class MakeCondition extends Command
{
	protected static $defaultName = 'make:condition';

	protected function configure ()
	{
		$this->setDescription('make a new condition');
		$this->addArgument('names', InputArgument::IS_ARRAY);
		$this->addUsage('MyCondition');
		$this->addUsage('MyCondition1 MyCondition2');
	}

	protected function execute (InputInterface $input, OutputInterface $output)
	{

		$conditions = $input->getArgument('names');

		foreach ($conditions as $condition)
		{
			$this->create_condition_class($condition);
		}

		$io = new SymfonyStyle($input, $output);
		$io->success('Done.');

		return Command::SUCCESS;
	}

	private function create_condition_class ($name)
	{
		file_put_contents(__DIR__ . "/Scenarios/Conditions/$name.php", "<?php


namespace Bot\Scenarios\Conditions;


use BotFramework\Scenarios\Conditions\Condition;

class $name extends Condition
{
	public function check (\Longman\TelegramBot\Entities\Update \$update) : bool
	{
		// check
	}
}
");
	}
}

class MakeModel extends Command
{
	protected static $defaultName = 'make:model';

	protected function configure ()
	{
		$this->setDescription('make a new model');
		$this->addArgument('names', InputArgument::IS_ARRAY, 'name or names (space separated)');
		$this->addUsage('User');
		$this->addUsage('UserChat');
	}

	protected function execute (InputInterface $input, OutputInterface $output) : int
	{
		$models = $input->getArgument('names');

		if (empty($models))
			throw new RuntimeException('Error. No name/names are entered.');

		foreach ($models as $model)
		{
			$this->create_model_class($model);
			$this->create_schema($model);
			$this->put_in_provider($model);
		}

		$io = new SymfonyStyle($input, $output);
		$io->success('Done.');

		return Command::SUCCESS;
	}

	private function create_model_class ($name)
	{
		file_put_contents(__DIR__ . "/Models/$name.php", "<?php


namespace Bot\Models;


use BotFramework\Models\Model;

class $name extends Model
{
	protected \$guarded = ['id'];
}
");
	}

	private function create_schema ($name)
	{
		$schema_name = "${name}Schema";
		$table = \BotFramework\Facilities\Str::plural(\BotFramework\Facilities\Str::snake($name));
		file_put_contents(__DIR__ . "/Database/Schemas/$schema_name.php", "<?php


namespace Bot\Database\Schemas;


use BotFramework\Database\Schemas\TableSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

class $schema_name extends TableSchema
{
	protected \$tableName = '$table';

	public function up ()
	{
		Capsule::schema()->create(\$this->tableName, function (Blueprint \$table) {
			\$table->increments('id');
			// columns
			\$table->timestamps();
		});
	}
}
");
	}

	private function put_in_provider ($name)
	{
		$schema_name = "${name}Schema";
		$content = file_get_contents(__DIR__ . '/Providers/SchemaProvider.php');
		$where_to_insert = strpos($content, ']') - 1;
		$what_to_insert = "\t\t\\Bot\\Database\\Schemas\\$schema_name::class,\n\t";
		$content = substr_replace($content, $what_to_insert, $where_to_insert, 0);
		file_put_contents(__DIR__ . '/Providers/SchemaProvider.php', $content);
	}
}

class DatabaseRebuild extends Command
{
	protected static $defaultName = 'database:rebuild';

	protected function configure ()
	{
		$this->setDescription('rebuild database tables using schemas');
	}

	protected function execute (InputInterface $input, OutputInterface $output) : int
	{
		$io = new SymfonyStyle($input, $output);
		$choice = $io->choice('Are you sure?', ['yes', 'no'], 'no');

		if ($choice == 'no')
			return Command::FAILURE;

		$this->rebuild_database();

		$io->success('Database is rebuilt successfully');

		return Command::SUCCESS;
	}

	private function rebuild_database ()
	{
		\Dotenv\Dotenv::createImmutable(__DIR__, 'config.env')->load();
		\BotFramework\Providers\DatabaseServiceProvider::boot();
		$schemas = \Bot\Providers\SchemaProvider::register();
		foreach ($schemas as $schema)
		{
			(new $schema)->down()->up();
		}
	}
}

$app = new Application('Atmosphere Assistant');

$app->addCommands([
	new MakeMiddleware(),
	new MakeScenario(),
	new MakeSubScenario(),
	new MakeCondition(),
	new MakeModel(),
	new DatabaseRebuild()
]);

$app->run();
