<?php
namespace envtesting {
	/**
	 * Group of basic asserts
	 *
	 * @author Roman Ozana <ozana@omdesign.cz>
	 */

	class
	Assert {
		static
		function
		same(
			$actual, $expected, $message = null
		) {
			if ($actual !== $expected) {
				throw
				new
				Error($message);
			}
		}

		static
		function
		fail(
			$message = null
		) {
			throw
			new
			Error($message);
		}

		static
		function
		true(
			$value, $message = null
		) {
			if ($value !== true) {
				throw
				new
				Error($message);
			}
		}

		static
		function
		false(
			$value, $message = null
		) {
			if ($value !== false) {
				throw
				new
				Error($message);
			}
		}
	}

	final
	class
	Autoloader {
		private
		static $update = true;
		private
		static $init = true;
		private
		static $paths = array();

		static
		function
		addPath(
			$path
		) {
			self::$paths[] = $path;
			self::$update = true;
		}

		static
		function
		cls(
			$className
		) {
			if (self::$init) {
				self::$paths[] = get_include_path();
				self::$paths[] = dirname(__DIR__);
				self::$init = false;
			}
			if (self::$update) {
				set_include_path(implode(PATH_SEPARATOR, self::$paths));
				self::$update = false;
			}
			$className = ltrim($className, '\\');
			$fileName = '';
			if ($lastNsPos = strripos($className, '\\')) {
				$namespace = substr($className, 0, $lastNsPos);
				$className = substr($className, $lastNsPos + 1);
				$fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
			}
			$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
			return (bool)@ include_once $fileName;
		}
	}

	spl_autoload_register(array('envtesting\Autoloader', 'cls'));
	class
	Check {
		static
		function
		lib(
			$extensionName, $infoFunction = ''
		) {
			return
				extension_loaded($extensionName) && ($infoFunction === '' || function_exists($infoFunction));
		}

		static
		function
		cls(
			$className
		) {
			return
				class_exists($className);
		}

		static
		function
		ini(
			$variable, $value = null
		) {
			return ($value === null) ? ini_get($variable) : $value === ini_get($variable);
		}

		static
		function
		file(
			$file, $dir = __DIR__
		) {
			return
				function() use($file, $dir) {
					include $dir . DIRECTORY_SEPARATOR . $file;
				};
		}
	}

	class
	Error
		extends \Exception {
	}

	class
	Warning
		extends \Exception {
	}

	class
	App {
		public
		static $root = __DIR__;
	}
}namespace envtesting\output {
	use
	envtesting\TestSuit;
	use
	envtesting\SuitGroup;

	class
	Html {
		private $layout = null;
		public $elements = array();
		public $title = 'Envtesting';

		function
		__construct(
			$title = 'Envtesting'
		) {
			$this->title = $title;
		}

		function
		add(
			$element
		) {
			$this->elements[] = $element;
			return $this;
		}

		function
		render() {
			extract((array)$this);?><!DOCTYPE html>
		<html lang="en-us" dir="ltr">
		<head>
			<meta charset="UTF-8">
			<title>Envtesting: <?=$title?></title>
			<link rel="stylesheet" type="text/css" media="all"
			      href="//current.bootstrapcdn.com/bootstrap-v204/css/bootstrap-combined.min.css"/>
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
			<script src="//current.bootstrapcdn.com/bootstrap-v204/js/bootstrap.min.js"></script>
			<style type="text/css">
				tr.warning {
					background: #F89406;
				}

				tr.error, tr.eception {
					background: #BD362F;
				}

				tr.ok {
					background: #5BB75B;
				}
			</style>
		</head>
		<body data-spy="scroll">

		<table class="table">
			<thead>
			<tr>
				<th>status</th>
				<th>name</th>
				<th>notice</th>
				<th>type</th>
				<th>options</th>
				<th>message</th>
			</tr>
			</thead>

			<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

			<?foreach ($elements
			           as $element) {
			?>
			<?foreach ($element
			           as $test) {
				?>
				<tr class="<?=strtolower($test->getStatus())?>">
					<td><?=$test->getStatus();?></td>
					<td><?=$test->getName();?></td>
					<td><?=$test->getNotice();?></td>
					<td><?=$test->getType();?></td>
					<td><code><?=json_encode((array)$test->getOptions());?></code></td>
					<td><?=$test->getStatusMessage();?></td>
				</tr>
				<? } ?>
			<? }?>

			<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

		</table>

		</body>
		</html><?php
		}
	}
}namespace envtesting {
	class
	SuitGroup
		implements \IteratorAggregate {
		protected $suits = array();
		protected $name = __CLASS__;

		function
		__construct(
			$name = __CLASS__
		) {
			$this->name = $name;
		}

		function
		getName() {
			return $this->name;
		}

		function
		shuffle() {
			shuffle($this->suits);
			return $this;
		}

		function
		addSuit(
			$name, $suit = null
		) {
			if (array_key_exists($name, $this->suits)) {
				throw
				new\Exception('TestSuit "' . $name . '" already exists');
			}
			return $this->suits[$name] = $suit ? $suit : TestSuit::instance($name);
		}

		function
		getIterator() {
			return
				new\ArrayIterator($this->suits);
		}

		function
		run() {
			foreach ($this->suits
			         as $suit) {
				$suit->run();
			}
			return $this;
		}

		function
		__toString() {
			return
				str_repeat('#', 80) . PHP_EOL . str_pad($this->name, 80, ' ', STR_PAD_BOTH) . PHP_EOL . implode(
					$this->suits, PHP_EOL
				) . PHP_EOL . str_repeat('#', 80) . PHP_EOL;
		}
	}

	class
	Test {
		protected $name = '';
		protected $callback = null;
		protected $type = null;
		protected $options = array();
		protected $notice = '';
		protected $result = null;

		function
		__construct(
			$name, $callback, $type = null
		) {
			$this->name = $name;
			$this->callback = $callback;
			$this->type = $type;
		}

		function
		withOptions() {
			$this->options = func_get_args();
			return $this;
		}

		function
		run() {
			try {
				$this->result = 'OK';
				call_user_func_array($this->getCallback(), $this->getOptions());
			} catch (Error$error) {
				$this->setResult($error);
			} catch (Warning$warning) {
				$this->setResult($warning);
			} catch (\Exception$e) {
				$this->setResult($e);
			}
			return $this;
		}

		function
		__invoke() {
			return $this->run();
		}

		function
		getStatus() {
			if (is_scalar($this->getResult())) return (string)$this->getResult();
			if ($this->isError()) return 'ERROR';
			if ($this->isWarning()) return 'WARNING';
			if ($this->isException()) return 'EXCEPTION';
			throw
			new\Exception('Invalid result type: ' . gettype($this->result));
		}

		function
		getStatusMessage() {
			return ($this->result
				instanceof\Exception) ? $this->result->getMessage() : '';
		}

		function
		isWarning() {
			return $this->getResult()instanceof
				Warning;
		}

		function
		isError() {
			return $this->getResult()instanceof
				Error;
		}

		function
		isOk() {
			return !$this->isException();
		}

		function
		isException() {
			return $this->getResult()instanceof\Exception;
		}

		function
		getResult() {
			if ($this->result === null) $this->run();
			return $this->result;
		}

		function
		setResult(
			$result
		) {
			$this->result = $result;
			return $this;
		}

		function
		__toString() {
			$response = array(
				'status' => str_pad($this->getStatus(), 10, ' '), 'name' => str_pad($this->getName(), 20, ' '),
				'type' => str_pad($this->getType(), 10, ' '), 'options' => json_encode((object)$this->getOptions()),
				'notice' => $this->getNotice(), 'message' => $this->getStatusMessage()
			);
			return
				implode($response, ' | ');
		}

		function
		getCallback() {
			if (is_callable($this->callback)) return $this->callback;
			if (is_string($this->callback) && strpos($this->callback, '::')) {
				return $this->callback = explode('::', $this->callback);
			}
			throw
			new\Exception('Invalid callback');
		}

		function
		setName(
			$name
		) {
			$this->name = $name;
			return $this;
		}

		function
		getName() {
			return $this->name;
		}

		function
		getType() {
			return $this->type;
		}

		function
		setType(
			$type
		) {
			$this->type = $type;
			return $this;
		}

		function
		getOptions() {
			return $this->options;
		}

		function
		setOptions(
			$options
		) {
			$this->options = $options;
			return $this;
		}

		function
		getNotice() {
			return $this->notice;
		}

		function
		setNotice(
			$notice
		) {
			$this->notice = $notice;
			return $this;
		}

		static
		function
		instance(
			$name, $callback, $type = null
		) {
			return
				new
				self($name, $callback, $type);
		}
	}

	class
	TestSuit
		implements \ArrayAccess, \IteratorAggregate {
		protected $tests = array();
		protected $name = __CLASS__;

		function
		__construct(
			$name = __CLASS__
		) {
			$this->name = $name;
		}

		function
		shuffle() {
			shuffle($this->tests);
			return $this;
		}

		function
		run() {
			foreach ($this->tests
			         as $key => $test) {
				$test->run();
			}
			return $this;
		}

		function
		__invoke() {
			return $this->run();
		}

		function
		getName() {
			return $this->name;
		}

		function
		addTest(
			$name, $callback, $type = null
		) {
			return $this->tests[] = Test::instance($name, $callback, $type);
		}

		function
		__toString() {
			return
				str_repeat(':', 80) . PHP_EOL . str_pad($this->name, 80, ' ', STR_PAD_BOTH) . PHP_EOL . str_repeat(
					':', 80
				) . PHP_EOL . implode(PHP_EOL, $this->tests) . PHP_EOL;
		}

		function
		fromDir(
			$dir, $type = ''
		) {
			$iterator = new\RegexIterator(new\RecursiveIteratorIterator(new\RecursiveDirectoryIterator($dir)), '/\.php$/i');
			foreach ($iterator
			         as $filePath => $fileInfo) {
				$this->addTest($fileInfo->getBasename('.php'), Check::file($filePath, ''))->setType($type);
			}
			return $this;
		}

		function
		offsetExists(
			$offset
		) {
			return
				array_key_exists($offset, $this->tests);
		}

		function
		offsetGet(
			$offset
		) {
			return $this->tests[$offset];
		}

		function
		offsetSet(
			$offset, $value
		) {
			if (!$value
				instanceof
				Test
			) {
				throw
				new\Exception('Usupported test type');
			}
			if ($offset === null) {
				$this->tests[] = $value;
			} else {
				$this->tests[$offset] = $value;
			}
		}

		function
		offsetUnset(
			$offset
		) {
			if (isset($this->tests[$offset])) {
				unset($this->tests[$offset]);
			}
		}

		function
		getIterator() {
			return
				new\ArrayIterator($this->tests);
		}

		static
		function
		instance(
			$name
		) {
			return
				new
				self($name);
		}
	}

	class
	Assert {
		static
		function
		same(
			$actual, $expected, $message = null
		) {
			if ($actual !== $expected) {
				throw
				new
				Error($message);
			}
		}

		static
		function
		fail(
			$message = null
		) {
			throw
			new
			Error($message);
		}

		static
		function
		true(
			$value, $message = null
		) {
			if ($value !== true) {
				throw
				new
				Error($message);
			}
		}

		static
		function
		false(
			$value, $message = null
		) {
			if ($value !== false) {
				throw
				new
				Error($message);
			}
		}
	}

	final
	class
	Autoloader {
		private
		static $update = true;
		private
		static $init = true;
		private
		static $paths = array();

		static
		function
		addPath(
			$path
		) {
			self::$paths[] = $path;
			self::$update = true;
		}

		static
		function
		cls(
			$className
		) {
			if (self::$init) {
				self::$paths[] = get_include_path();
				self::$paths[] = dirname(__DIR__);
				self::$init = false;
			}
			if (self::$update) {
				set_include_path(implode(PATH_SEPARATOR, self::$paths));
				self::$update = false;
			}
			$className = ltrim($className, '\\');
			$fileName = '';
			if ($lastNsPos = strripos($className, '\\')) {
				$namespace = substr($className, 0, $lastNsPos);
				$className = substr($className, $lastNsPos + 1);
				$fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
			}
			$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
			return (bool)@ include_once $fileName;
		}
	}

	spl_autoload_register(array('envtesting\Autoloader', 'cls'));
	class
	Check {
		static
		function
		lib(
			$extensionName, $infoFunction = ''
		) {
			return
				extension_loaded($extensionName) && ($infoFunction === '' || function_exists($infoFunction));
		}

		static
		function
		cls(
			$className
		) {
			return
				class_exists($className);
		}

		static
		function
		ini(
			$variable, $value = null
		) {
			return ($value === null) ? ini_get($variable) : $value === ini_get($variable);
		}

		static
		function
		file(
			$file, $dir = __DIR__
		) {
			return
				function() use($file, $dir) {
					include $dir . DIRECTORY_SEPARATOR . $file;
				};
		}
	}

	class
	Error
		extends \Exception {
	}

	class
	Warning
		extends \Exception {
	}

	class
	App {
		public
		static $root = __DIR__;
	}
}namespace envtesting\output {
	use
	envtesting\TestSuit;
	use
	envtesting\SuitGroup;

	class
	Html {
		private $layout = null;
		public $elements = array();
		public $title = 'Envtesting';

		function
		__construct(
			$title = 'Envtesting'
		) {
			$this->title = $title;
		}

		function
		add(
			$element
		) {
			$this->elements[] = $element;
			return $this;
		}

		function
		render() {
			extract((array)$this);?><!DOCTYPE html>
		<html lang="en-us" dir="ltr">
		<head>
			<meta charset="UTF-8">
			<title>Envtesting: <?=$title?></title>
			<link rel="stylesheet" type="text/css" media="all"
			      href="//current.bootstrapcdn.com/bootstrap-v204/css/bootstrap-combined.min.css"/>
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
			<script src="//current.bootstrapcdn.com/bootstrap-v204/js/bootstrap.min.js"></script>
			<style type="text/css">
				tr.warning {
					background: #F89406;
				}

				tr.error, tr.eception {
					background: #BD362F;
				}

				tr.ok {
					background: #5BB75B;
				}
			</style>
		</head>
		<body data-spy="scroll">

		<table class="table">
			<thead>
			<tr>
				<th>status</th>
				<th>name</th>
				<th>notice</th>
				<th>type</th>
				<th>options</th>
				<th>message</th>
			</tr>
			</thead>

			<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

			<?foreach ($elements
			           as $element) {
			?>
			<?foreach ($element
			           as $test) {
				?>
				<tr class="<?=strtolower($test->getStatus())?>">
					<td><?=$test->getStatus();?></td>
					<td><?=$test->getName();?></td>
					<td><?=$test->getNotice();?></td>
					<td><?=$test->getType();?></td>
					<td><code><?=json_encode((array)$test->getOptions());?></code></td>
					<td><?=$test->getStatusMessage();?></td>
				</tr>
				<? } ?>
			<? }?>

			<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

		</table>

		</body>
		</html><?php
		}
	}
}namespace envtesting {
	class
	SuitGroup
		implements \IteratorAggregate {
		protected $suits = array();
		protected $name = __CLASS__;

		function
		__construct(
			$name = __CLASS__
		) {
			$this->name = $name;
		}

		function
		getName() {
			return $this->name;
		}

		function
		shuffle() {
			shuffle($this->suits);
			return $this;
		}

		function
		addSuit(
			$name, $suit = null
		) {
			if (array_key_exists($name, $this->suits)) {
				throw
				new\Exception('TestSuit "' . $name . '" already exists');
			}
			return $this->suits[$name] = $suit ? $suit : TestSuit::instance($name);
		}

		function
		getIterator() {
			return
				new\ArrayIterator($this->suits);
		}

		function
		run() {
			foreach ($this->suits
			         as $suit) {
				$suit->run();
			}
			return $this;
		}

		function
		__toString() {
			return
				str_repeat('#', 80) . PHP_EOL . str_pad($this->name, 80, ' ', STR_PAD_BOTH) . PHP_EOL . implode(
					$this->suits, PHP_EOL
				) . PHP_EOL . str_repeat('#', 80) . PHP_EOL;
		}
	}

	class
	Test {
		protected $name = '';
		protected $callback = null;
		protected $type = null;
		protected $options = array();
		protected $notice = '';
		protected $result = null;

		function
		__construct(
			$name, $callback, $type = null
		) {
			$this->name = $name;
			$this->callback = $callback;
			$this->type = $type;
		}

		function
		withOptions() {
			$this->options = func_get_args();
			return $this;
		}

		function
		run() {
			try {
				$this->result = 'OK';
				call_user_func_array($this->getCallback(), $this->getOptions());
			} catch (Error$error) {
				$this->setResult($error);
			} catch (Warning$warning) {
				$this->setResult($warning);
			} catch (\Exception$e) {
				$this->setResult($e);
			}
			return $this;
		}

		function
		__invoke() {
			return $this->run();
		}

		function
		getStatus() {
			if (is_scalar($this->getResult())) return (string)$this->getResult();
			if ($this->isError()) return 'ERROR';
			if ($this->isWarning()) return 'WARNING';
			if ($this->isException()) return 'EXCEPTION';
			throw
			new\Exception('Invalid result type: ' . gettype($this->result));
		}

		function
		getStatusMessage() {
			return ($this->result
				instanceof\Exception) ? $this->result->getMessage() : '';
		}

		function
		isWarning() {
			return $this->getResult()instanceof
				Warning;
		}

		function
		isError() {
			return $this->getResult()instanceof
				Error;
		}

		function
		isOk() {
			return !$this->isException();
		}

		function
		isException() {
			return $this->getResult()instanceof\Exception;
		}

		function
		getResult() {
			if ($this->result === null) $this->run();
			return $this->result;
		}

		function
		setResult(
			$result
		) {
			$this->result = $result;
			return $this;
		}

		function
		__toString() {
			$response = array(
				'status' => str_pad($this->getStatus(), 10, ' '), 'name' => str_pad($this->getName(), 20, ' '),
				'type' => str_pad($this->getType(), 10, ' '), 'options' => json_encode((object)$this->getOptions()),
				'notice' => $this->getNotice(), 'message' => $this->getStatusMessage()
			);
			return
				implode($response, ' | ');
		}

		function
		getCallback() {
			if (is_callable($this->callback)) return $this->callback;
			if (is_string($this->callback) && strpos($this->callback, '::')) {
				return $this->callback = explode('::', $this->callback);
			}
			throw
			new\Exception('Invalid callback');
		}

		function
		setName(
			$name
		) {
			$this->name = $name;
			return $this;
		}

		function
		getName() {
			return $this->name;
		}

		function
		getType() {
			return $this->type;
		}

		function
		setType(
			$type
		) {
			$this->type = $type;
			return $this;
		}

		function
		getOptions() {
			return $this->options;
		}

		function
		setOptions(
			$options
		) {
			$this->options = $options;
			return $this;
		}

		function
		getNotice() {
			return $this->notice;
		}

		function
		setNotice(
			$notice
		) {
			$this->notice = $notice;
			return $this;
		}

		static
		function
		instance(
			$name, $callback, $type = null
		) {
			return
				new
				self($name, $callback, $type);
		}
	}

	class
	TestSuit
		implements \ArrayAccess, \IteratorAggregate {
		protected $tests = array();
		protected $name = __CLASS__;

		function
		__construct(
			$name = __CLASS__
		) {
			$this->name = $name;
		}

		function
		shuffle() {
			shuffle($this->tests);
			return $this;
		}

		function
		run() {
			foreach ($this->tests
			         as $key => $test) {
				$test->run();
			}
			return $this;
		}

		function
		__invoke() {
			return $this->run();
		}

		function
		getName() {
			return $this->name;
		}

		function
		addTest(
			$name, $callback, $type = null
		) {
			return $this->tests[] = Test::instance($name, $callback, $type);
		}

		function
		__toString() {
			return
				str_repeat(':', 80) . PHP_EOL . str_pad($this->name, 80, ' ', STR_PAD_BOTH) . PHP_EOL . str_repeat(
					':', 80
				) . PHP_EOL . implode(PHP_EOL, $this->tests) . PHP_EOL;
		}

		function
		fromDir(
			$dir, $type = ''
		) {
			$iterator = new\RegexIterator(new\RecursiveIteratorIterator(new\RecursiveDirectoryIterator($dir)), '/\.php$/i');
			foreach ($iterator
			         as $filePath => $fileInfo) {
				$this->addTest($fileInfo->getBasename('.php'), Check::file($filePath, ''))->setType($type);
			}
			return $this;
		}

		function
		offsetExists(
			$offset
		) {
			return
				array_key_exists($offset, $this->tests);
		}

		function
		offsetGet(
			$offset
		) {
			return $this->tests[$offset];
		}

		function
		offsetSet(
			$offset, $value
		) {
			if (!$value
				instanceof
				Test
			) {
				throw
				new\Exception('Usupported test type');
			}
			if ($offset === null) {
				$this->tests[] = $value;
			} else {
				$this->tests[$offset] = $value;
			}
		}

		function
		offsetUnset(
			$offset
		) {
			if (isset($this->tests[$offset])) {
				unset($this->tests[$offset]);
			}
		}

		function
		getIterator() {
			return
				new\ArrayIterator($this->tests);
		}

		static
		function
		instance(
			$name
		) {
			return
				new
				self($name);
		}
	}
}