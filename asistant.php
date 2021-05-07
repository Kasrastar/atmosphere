<?php

require_once __DIR__ . '/vendor/autoload.php';


use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Exception\RuntimeException as RuntimeException;

abstract class MakeCommand extends Command
{
	protected $template;

	/**
	 * In the form of 'path/to/folder/'
	 *
	 * @var string
	 */
	protected $componentDirectoryPath;

	protected $providerType;
	protected $providerNamespace;

	protected function create_component ($var)
	{
		if (is_array($var))
		{
			$path_to_file = __DIR__ . '/' . $this->componentDirectoryPath . $var['$name'] . '.php';
			$keys = array_keys($var);

			foreach ($keys as $key)
			{
				$this->template = str_replace($key, $var[$key], $this->template);
			}

			$content = $this->template;
		}

		else
		{
			$path_to_file = __DIR__ . '/' . $this->componentDirectoryPath . "$var.php";
			$content = str_replace('$name', $var, $this->template);
		}
		file_put_contents($path_to_file, $content);
	}

	protected function put_in_provider ($name)
	{
		$path_to_provider = __DIR__ . '/Providers/' . $this->providerType . 'Provider.php';

		$content = file_get_contents($path_to_provider);

		$where_to_insert = strpos($content, ']') - 1;
		$what_to_insert = "\t\t\\Bot\\$this->providerNamespace\\$name::class,\n\t";
		$content = substr_replace($content, $what_to_insert, $where_to_insert, 0);

		file_put_contents($path_to_provider, $content);
	}

	protected function configure ()
	{
		$this->addArgument('names', InputArgument::IS_ARRAY, 'name or names (space separated)');
		$this->addUsage('Foo');
		$this->addUsage('Foo Bar');
	}

	protected function execute (InputInterface $input, OutputInterface $output)
	{
		$components = $input->getArgument('names');

		if (empty($components))
			throw new RuntimeException('Error. No name/names are entered.');

		foreach ($components as $component)
		{
			$this->create_component($component);
			if ( ! is_null($this->providerType))
				$this->put_in_provider($component);
		}

		$io = new SymfonyStyle($input, $output);
		$io->success('Done.');

		return Command::SUCCESS;
	}
}

class MakeMiddleware extends MakeCommand
{
	protected $componentDirectoryPath = 'Middlewares/';
	protected $providerType = 'Middleware';
	protected $providerNamespace = 'Middlewares';
	protected $template = '<?php


namespace Bot\Middlewares;


use BotFramework\Middlewares\Middleware;
use Longman\TelegramBot\Entities\Update;

class $name extends Middleware
{
	public function allow (Update $update) : bool
	{
		// handle
	}
}
';

	protected static $defaultName = 'make:middleware';

	protected function configure ()
	{
		$this->setDescription('make a new middleware');
		parent::configure();
	}
}

class MakeScenario extends MakeCommand
{
	protected $componentDirectoryPath = 'Scenarios/';
	protected $providerType = 'Scenario';
	protected $providerNamespace = 'Scenarios';
	protected $template = '<?php


namespace Bot\Scenarios;


use BotFramework\Scenarios\Scenario;
use Longman\TelegramBot\Entities\Update;

class $name extends Scenario
{
	protected \$conditions = [
		// condition classes here
	];

	protected function handle (Update $update)
	{
		// handle
	}
}
';

	protected static $defaultName = 'make:scenario';

	protected function configure ()
	{
		$this->setDescription('make a new scenario');
		parent::configure();
	}
}

class MakeSubScenario extends MakeCommand
{
	protected $componentDirectoryPath = 'Scenarios/SubScenarios/';
	protected $template = '?php


namespace Bot\Scenarios\SubScenarios;


use BotFramework\Scenarios\SubScenarios\SubScenario;
use Longman\TelegramBot\Entities\Update;

class $name extends SubScenario
{
	protected function handle (Update $update)
	{
		// handle
	}
}
';

	protected static $defaultName = 'make:sub-scenario';

	protected function configure ()
	{
		$this->setDescription('make a new sub scenario');
		parent::configure();
	}
}

class MakeCondition extends MakeCommand
{
	protected $componentDirectoryPath = 'Scenarios/Conditions/';
	protected $template = '<?php


namespace Bot\Scenarios\Conditions;


use BotFramework\Scenarios\Conditions\Condition;
use Longman\TelegramBot\Entities\Update;

class $name extends Condition
{
	public function check (Update $update) : bool
	{
		// check
	}
}
';

	protected static $defaultName = 'make:condition';

	protected function configure ()
	{
		$this->setDescription('make a new condition');
		parent::configure();
	}
}

class MakeModel extends MakeCommand
{
	protected $componentDirectoryPath = 'Models/';
	protected $template = "<?php


namespace Bot\Models;


use BotFramework\Models\Model;

class \$name extends Model
{
	protected \$guarded = ['id'];
}
";

	protected static $defaultName = 'make:model';

	protected function configure ()
	{
		$this->setDescription('make a new model');
		parent::configure();
	}

	protected function execute (InputInterface $input, OutputInterface $output)
	{
		parent::execute($input, $output);

		$command = $this->getApplication()->find('make:schema');
		$arguments = $input->getArguments();

		return $command->run(new ArrayInput($arguments), $output);
	}
}

class MakeSchema extends MakeCommand
{
	protected $componentDirectoryPath = 'Database/Schemas/';
	protected $providerType = 'Schema';
	protected $providerNamespace = 'Database\\Schemas';
	protected $template = "<?php


namespace Bot\Database\Schemas;


use BotFramework\Database\Schemas\TableSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

class \$name extends TableSchema
{
	protected \$tableName = '\$table_name';

	public function up ()
	{
		Capsule::schema()->create(\$this->tableName, function (Blueprint \$table) {
			\$table->increments('id');
			// columns
			\$table->timestamps();
		});
	}
}
";

	protected function create_component($component)
	{
		$schema_name = "${component}Schema";
		$table = \BotFramework\Facilities\Str::plural(\BotFramework\Facilities\Str::snake($component));

		parent::create_component([
			'$name' => $schema_name,
			'$table_name' => $table
		]);
	}

	protected function put_in_provider ($name)
	{
		parent::put_in_provider("${name}Schema");
	}

	protected static $defaultName = 'make:schema';

	protected function configure ()
	{
		$this->setDescription('make a new schema');
		parent::configure();
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
		$choice = $io->choice('Are you sure?', [ 'yes', 'no' ], 'no');

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
			( new $schema )->down()->up();
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
	new MakeSchema(),
	new DatabaseRebuild(),
]);

$app->run();
