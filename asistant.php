<?php

require_once __DIR__ . '/vendor/autoload.php';

use Stringy\Stringy;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\ClassType;
use Bot\Providers\MiddlewareProvider;
use Atmosphere\Middlewares\Middleware;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Longman\TelegramBot\Entities\Update;
use Bot\Providers\ScenarioProvider;
use Bot\Providers\SchemaProvider;
use Atmosphere\Scenarios\Conditions\Condition;
use Atmosphere\Database\Schemas\TableSchema;
use Atmosphere\Supports\Traits\PropertyInjection;
use Atmosphere\Providers\DatabaseServiceProvider;
use Atmosphere\Scenarios\Scenario;
use Atmosphere\Scenarios\SubScenarios\SubScenario;
use Atmosphere\Views\View;
use Atmosphere\Supports\Str;
use Atmosphere\Models\Model;
use Atmosphere\Providers\Boot;


// Class Makers
// ----------------------------------------------------------------------------------------
interface HasProvider
{
	/**
	 * @param string $component
	 *
	 * @return string
	 */
	public function prepareProvider ($component);
}

abstract class ClassMaker
{
	/**
	 * @var string
	 */
	protected $componentDirectory;

	/**
	 * If has provider
	 *
	 * @var string
	 */
	protected $providerPath;

	/**
	 * Create component file and put it's content
	 *
	 * @param string $component
	 */
	final public function make ($component)
	{
		// put in provider
		if ($this instanceof HasProvider)
			file_put_contents($this->providerPath, $this->prepareProvider($component));

		file_put_contents("$this->componentDirectory/$component.php", $this->prepare($component));
	}

	/**
	 * @param string $component
	 *
	 * @return string
	 */
	abstract protected function prepare ($component);

	/**
	 * @param array $array
	 *
	 * @return string
	 */
	final protected function convertArrayToString ($array)
	{
		array_walk($array, function (&$value, $index) {
			$value = (string) Stringy::create($value)->prepend("\n\t\\")->append("::class,");
		});

		return implode('', $array);
	}
}

class ScenarioMaker extends ClassMaker implements HasProvider
{
	protected $componentDirectory = 'App/Scenarios';
	protected $providerPath = 'Providers/ScenarioProvider.php';

	protected function prepare ($component)
	{
		$file = new PhpFile;

		$namespace = $file->addNamespace('Bot\App\Scenarios')
			->addUse(Scenario::class)
			->addUse(Update::class);

		$class = $namespace->addClass($component)
			->setExtends(Scenario::class);

		$class->addProperty('conditions', [])
			->addComment('Conditions which update should compatible with' . PHP_EOL)
			->addComment('@var array')
			->setProtected();

		$method = $class->addMethod('handle')
			->addComment('Handle this scenario here' . PHP_EOL)
			->addComment('@return void')
			->setProtected()
			->setBody('// handle');

		$method->addParameter('update')
			->setType(Update::class);

		return (string) $file;
	}

	public function prepareProvider ($component)
	{
		$scenarios = ( new ReflectionClass(ScenarioProvider::class) )
			->getMethod('register')->invoke(null);;

		$scenarios = $this->convertArrayToString(array_merge($scenarios, [ "Bot\\App\\Scenarios\\$component" ]));

		$body = "return [$scenarios\n];";

		$file = new PhpFile;
		$file->addNamespace('Bot\Providers');

		$provider_class = ClassType::from(ScenarioProvider::class);
		$provider_class->getMethod('register')->setBody($body);

		return $file . PHP_EOL . PHP_EOL . $provider_class;
	}
}

class SubScenarioMaker extends ClassMaker
{
	protected $componentDirectory = 'App/Scenarios/SubScenarios';

	protected function prepare ($component)
	{
		$file = new PhpFile;

		$namespace = $file->addNamespace('Bot\App\Scenarios\SubScenarios')
			->addUse(SubScenario::class)
			->addUse(Update::class);

		$class = $namespace->addClass($component)
			->setExtends(SubScenario::class);

		$method = $class->addMethod('handle')
			->addComment('Handle this sub scenario here' . PHP_EOL)
			->addComment('@return void')
			->setProtected()
			->setBody('// handle');

		$method->addParameter('update')
			->setType(Update::class);

		return (string) $file;
	}
}

class ConditionMaker extends ClassMaker
{
	protected $componentDirectory = 'App/Scenarios/Conditions';

	protected function prepare ($component)
	{
		$file = new PhpFile;

		$namespace = $file->addNamespace('Bot\App\Scenarios\Conditions')
			->addUse(Condition::class)
			->addUse(Update::class);

		$class = $namespace->addClass($component)
			->setExtends(Condition::class);

		$method = $class->addMethod('check')
			->addComment('Check incoming update condition' . PHP_EOL)
			->addComment('@return bool')
			->setProtected()
			->setBody('// do stuff');

		$method->addParameter('update')
			->setType(Update::class);

		return (string) $file;
	}
}

class ModelMaker extends ClassMaker
{
	protected $componentDirectory = 'App/Models';

	protected function prepare ($component)
	{
		$file = new PhpFile;

		$namespace = $file->addNamespace('Bot\App\Models')
			->addUse(Model::class);

		$class = $namespace->addClass($component)
			->setExtends(Model::class);

		$class->addProperty('gaurded', [ 'id' ])->setProtected();

		return (string) $file;
	}
}

class SchemaMaker extends ClassMaker implements HasProvider
{
	protected $componentDirectory = 'Database/Schemas';
	protected $providerPath = 'Providers/SchemaProvider.php';

	protected function prepare ($component)
	{
		$file = new PhpFile;

		$namespace = $file->addNamespace('Bot\Database\Schemas')
			->addUse(TableSchema::class)
			->addUse(Blueprint::class)
			->addUse(Manager::class);

		$class = $namespace->addClass($component)
			->setExtends(TableSchema::class);

		$str = Str::getInstance();
		$table_name = $str->plural($str->snake($component));

		$class->addProperty('tableName', $table_name)->setProtected();

		$class->addMethod('up')
			->setComment('Schema definition')
			->setComment('@return void')
			->setPublic()
			->addBody('Manager::schema()->create($this->tableName, function (Blueprint $table) {')
			->addBody("\t\$table->id();")
			->addBody("\t\$table->timestamps();")
			->addBody('});');

		return (string) $file;
	}

	public function prepareProvider ($component)
	{
		$schemas = ( new ReflectionClass(SchemaProvider::class) )
			->getMethod('register')->invoke(null);

		$schemas = $this->convertArrayToString(array_merge($schemas, [ "Bot\\Database\\Schemas\\$component" ]));

		$body = "return [$schemas\n];";

		$file = new PhpFile;
		$file->addNamespace('Bot\Providers');

		$provider_class = ClassType::from(SchemaProvider::class);
		$provider_class->getMethod('register')->setBody($body);

		return $file . PHP_EOL . PHP_EOL . $provider_class;
	}
}

class ViewMaker extends ClassMaker
{
	protected $componentDirectory = 'App/Views';

	protected function prepare ($component)
	{
		$file = new PhpFile;

		$namespace = $file->addNamespace('Bot\App\Views')
			->addUse(View::class)
			->addUse(PropertyInjection::class);

		$class = $namespace->addClass($component)
			->setExtends(View::class);

		$method = $class->addMethod('template')
			->addComment('The view template' . PHP_EOL)
			->addComment('@return void')
			->setProtected()
			->setBody('// $this->add();');

		return (string) $file;
	}
}

class MiddlewareMaker extends ClassMaker implements HasProvider
{
	protected $componentDirectory = 'App/Middlewares';
	protected $providerPath = 'Providers/MiddlewareProvider.php';

	protected function prepare ($component)
	{
		$file = new PhpFile;

		$namespace = $file->addNamespace('Bot\App\Middlewares')
			->addUse(Middleware::class)
			->addUse(Update::class);

		$class = $namespace->addClass($component)
			->setExtends(Middleware::class);

		$method = $class->addMethod('allow')
			->addComment('Allow incoming update with specific condition' . PHP_EOL)
			->addComment('@return bool')
			->setPublic()
			->setBody('// return true if you want to allow');

		$method->addParameter('update')
			->setType(Update::class);

		return (string) $file;
	}

	public function prepareProvider ($component)
	{
		$middlewares = ( new ReflectionClass(MiddlewareProvider::class) )
			->getMethod('register')->invoke(null);;

		$middlewares = $this->convertArrayToString(array_merge($middlewares, [ "Bot\\App\\Middlewares\\$component" ]));

		$body = "return [$middlewares\n];";

		$file = new PhpFile;
		$file->addNamespace('Bot\Providers');

		$provider_class = ClassType::from(MiddlewareProvider::class);
		$provider_class->getMethod('register')->setBody($body);

		return $file . PHP_EOL . PHP_EOL . $provider_class;
	}
}

// ----------------------------------------------------------------------------------------


// Make Commands
// ----------------------------------------------------------------------------------------
abstract class MakeComponent extends Command
{
	protected $description;

	protected function configure ()
	{
		$this->setDescription($this->description);
		$this->addArgument('names', InputArgument::IS_ARRAY, 'name/names');
		$this->addUsage('Foo');
		$this->addUsage('Foo Bar');
	}

	protected function execute (InputInterface $input, OutputInterface $output)
	{
		$io = new SymfonyStyle($input, $output);
		$components = $input->getArgument('names');

		if (empty($components))
		{
			$io->error('No name/names found');
			return Command::FAILURE;
		}

		foreach ($components as $component)
		{
			$this->callMaker($component, $input);
		}

		$io->success('Done.');
		return Command::SUCCESS;
	}

	abstract protected function callMaker ($component, InputInterface $input);
}

class MakeScenario extends MakeComponent
{
	protected static $defaultName = 'make:scenario';
	protected $description = 'Make a new scenario';

	protected function callMaker ($component, InputInterface $input)
	{
		( new ScenarioMaker )->make($component);
	}
}

class MakeSubScenario extends MakeComponent
{
	protected static $defaultName = 'make:sub-scenario';
	protected $description = 'Make a new sub scenario';


	protected function callMaker ($component, InputInterface $input)
	{
		( new SubScenarioMaker )->make($component);
	}
}

class MakeCondition extends MakeComponent
{
	protected static $defaultName = 'make:condition';
	protected $description = 'Make a new condition';


	protected function callMaker ($component, InputInterface $input)
	{
		( new ConditionMaker )->make($component);
	}
}

class MakeModel extends MakeComponent
{
	protected static $defaultName = 'make:model';
	protected $description = 'Make a new model';

	protected function configure ()
	{
		$this->addOption('schema', 's', InputOption::VALUE_NONE,
			'Make schema with the model');
		parent::configure();
	}

	protected function callMaker ($component, InputInterface $input)
	{
		if ($input->getOption('schema'))
		{
			$command = $this->getApplication()->find('make:schema');

			$arguments = [
				'names' => [$component],
			];

			$greetInput = new ArrayInput($arguments);
			$command->run($greetInput, new NullOutput);
		}

		( new ModelMaker )->make($component);
	}
}

class MakeSchema extends MakeComponent
{
	protected static $defaultName = 'make:schema';
	protected $description = 'Make a new schema';

	protected function callMaker ($component, InputInterface $input)
	{
		( new SchemaMaker )->make($component);
	}
}

class MakeView extends MakeComponent
{
	protected static $defaultName = 'make:view';
	protected $description = 'Make a new view';


	protected function callMaker ($component, InputInterface $input)
	{
		( new ViewMaker )->make($component);
	}
}

class MakeMiddleware extends MakeComponent
{
	protected static $defaultName = 'make:middleware';
	protected $description = 'Make a new middleware';

	protected function callMaker ($component, InputInterface $input)
	{
		( new MiddlewareMaker )->make($component);
	}
}

class InitCommand extends Command
{
	private $patterns = [
		'Database/Schemas',
		'App/Middlewares',
		'App/Models',
		'App/Scenarios/SubScenarios',
		'App/Scenarios/Conditions',
		'App/Channels',
		'App/Views',
	];

	protected function configure ()
	{
		$this->setDescription('initialize directories');
		$this->addUsage('init');
	}

	protected static $defaultName = 'init';

	protected function execute (InputInterface $input, OutputInterface $output)
	{
		foreach ($this->patterns as $pattern)
		{
			mkdir($pattern, 0777, true);
		}

		return Command::SUCCESS;
	}
}

class DatabaseBuild extends Command
{
	protected static $defaultName = 'database:build';

	protected function configure ()
	{
		$this->setDescription('build database tables using schemas');
	}

	protected function execute (InputInterface $input, OutputInterface $output)
	{
		$io = new SymfonyStyle($input, $output);

		$this->build_database();

		$io->success('Database built successfully');

		return Command::SUCCESS;
	}

	private function build_database ()
	{
		Boot::turnOn();
		DatabaseServiceProvider::build(false);
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

		$io->success('Database rebuilt successfully');

		return Command::SUCCESS;
	}

	private function rebuild_database ()
	{
		Boot::turnOn();
		DatabaseServiceProvider::build(true);
	}
}

// ----------------------------------------------------------------------------------------

$app = new Application('Atmosphere Assistant');

$app->addCommands([
	new MakeMiddleware(),
	new MakeScenario(),
	new MakeSubScenario(),
	new MakeCondition(),
	new MakeModel(),
	new MakeSchema(),
	new MakeView(),
	new InitCommand(),
	new DatabaseBuild(),
	new DatabaseRebuild(),
]);

$app->run();
