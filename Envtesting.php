<?php
namespace envtesting{/**
 * Envtesting is fast simple and easy to use environment testing written in PHP.
 * Can check library, services and services response.
 *
 * Produce console, HTML or CSV output.
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 * @license MIT
 */

class
App{public
static$root=__DIR__;static
function
header($text,$ch=':'){return
str_repeat($ch,80).PHP_EOL.str_pad($text,80,' ',STR_PAD_BOTH).PHP_EOL.str_repeat($ch,80).PHP_EOL;}}class
Assert{static
function
same($actual,$expected,$message=null){if($actual!==$expected){throw
new
Error($message);}}static
function
fail($message=null){throw
new
Error($message);}static
function
true($value,$message=null){if($value!==true){throw
new
Error($message);}}static
function
false($value,$message=null){if($value!==false){throw
new
Error($message);}}}final
class
Autoloader{private
static$update=true;private
static$init=true;private
static$paths=array();static
function
addPath($path){self::$paths[]=$path;self::$update=true;}static
function
cls($className){if(self::$init){self::$paths[]=get_include_path();self::$paths[]=dirname(__DIR__);self::$init=false;}if(self::$update){set_include_path(implode(PATH_SEPARATOR,self::$paths));self::$update=false;}$className=ltrim($className,'\\');$fileName='';if($lastNsPos=strripos($className,'\\')){$namespace=substr($className,0,$lastNsPos);$className=substr($className,$lastNsPos+1);$fileName=str_replace('\\',DIRECTORY_SEPARATOR,$namespace).DIRECTORY_SEPARATOR;}$fileName.=str_replace('_',DIRECTORY_SEPARATOR,$className).'.php';return(bool)@ include_once $fileName;}}spl_autoload_register(array('envtesting\Autoloader','cls'));class
Check{static
function
lib($extensionName,$infoFunction=''){return
extension_loaded($extensionName)&&($infoFunction===''||function_exists($infoFunction));}static
function
cls($className){return
class_exists($className);}static
function
ini($variable,$value=null){return($value===null)?ini_get($variable):$value===ini_get($variable);}static
function
file($file,$dir=__DIR__){return
function()use($file,$dir){ include $dir . DIRECTORY_SEPARATOR . $file;};}}class
Error
extends\Exception{}}namespace envtesting\output{use
envtesting\Tests;use
envtesting\SuitGroup;final
class
Html{public$elements=array();public$title='Envtesting';function
__construct($title='Envtesting'){$this->title=$title;}function
add($element){$this->elements[]=$element;return$this;}function
render(){extract((array)$this);?><!DOCTYPE html>
<html lang="en-us" dir="ltr">
<head>
	<meta charset="UTF-8">
	<title><?=$title?></title>
	<meta name="robots" content="noindex, nofollow, noarchive, noodp"/>
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

<table class="table table-condensed">
	<thead>
	<tr>
		<th colspan="2">Status</th>
		<th title="Unique test name">Name</th>
		<th title="Notice eg. stable server">Notice</th>
		<th title="Test type">Type</th>
		<th title="Test parameters">Options</th>
		<th title="Response message">Message</th>
	</tr>
	</thead>

	<tbody>

	<?foreach($elements
as$element){?>
		<?foreach($element
as$order=>$test){?>
		<tr class="<?=strtolower($test->getStatus())?>">
			<td>
				</a><span class="icon-<?=$test->isOk()?'ok':'remove'?>"></span>
			</td>
			<td></span><?=$test->getStatus();?></td>
			<td><?=$test->getName();?></td>
			<td><?=$test->getNotice();?></td>
			<td><?=$test->getType();?></td>
			<td>
				<?if($test->hasOptions()){?>
				<code><?=json_encode((array)$test->getOptions());?></code>
				<?}?>
			</td>
			<td><?=$test->getStatusMessage();?></td>
		</tr>
			<?}?>
		<?}?>


	</tbody>
</table>

</body>
<!--
	Generated at <?=date('j.n.Y H:i:s')?> by Envtesting
	https://github.com/wikidi/envtesting
-->
</html><?php }static
function
instance($title='Envtesting'){return
new
self($title);}}}namespace envtesting{class
Test{protected$name='';protected$callback=null;protected$type=null;protected$options=array();protected$notice='';protected$result=null;function
__construct($name,$callback,$type=null){$this->name=$name;$this->callback=$callback;$this->type=$type;}function
withOptions(){$this->options=func_get_args();return$this;}function
run(){try{$this->result='OK';call_user_func_array($this->getCallback(),$this->getOptions());}catch(Error$error){$this->setResult($error);}catch(Warning$warning){$this->setResult($warning);}catch(\Exception$e){$this->setResult($e);}return$this;}function
__invoke(){return$this->run();}function
getStatus(){if(is_scalar($this->getResult()))return(string)$this->getResult();if($this->isError())return'ERROR';if($this->isWarning())return'WARNING';if($this->isException())return'EXCEPTION';throw
new\Exception('Invalid result type: '.gettype($this->result));}function
getStatusMessage(){return($this->result
instanceof\Exception)?$this->result->getMessage():'';}function
isWarning(){return$this->getResult()instanceof
Warning;}function
isError(){return$this->getResult()instanceof
Error;}function
isOk(){return!$this->isException();}function
isException(){return$this->getResult()instanceof\Exception;}function
getResult(){if($this->result===null)$this->run();return$this->result;}function
setResult($result){$this->result=$result;return$this;}function
__toString(){$response=array('status'=>str_pad($this->getStatus(),10,' '),'name'=>str_pad($this->getName(),20,' '),'type'=>str_pad($this->getType(),10,' '),'options'=>$this->hasOptions()?json_encode((object)$this->getOptions()):'','notice'=>$this->getNotice(),'message'=>$this->getStatusMessage());return
implode($response,' | ');}function
getCallback(){if(is_callable($this->callback))return$this->callback;if(is_string($this->callback)&&strpos($this->callback,'::')){return$this->callback=explode('::',$this->callback);}throw
new\Exception('Invalid callback');}function
setName($name){$this->name=$name;return$this;}function
getName(){return$this->name;}function
getType(){return$this->type;}function
setType($type){$this->type=$type;return$this;}function
getOptions(){return$this->options;}function
hasOptions(){return!empty($this->options);}function
setOptions($options){$this->options=$options;return$this;}function
getNotice(){return$this->notice;}function
setNotice($notice){$this->notice=$notice;return$this;}static
function
instance($name,$callback,$type=null){return
new
self($name,$callback,$type);}}class
Tests
implements\ArrayAccess,\IteratorAggregate{protected$tests=array();protected$name=__CLASS__;protected$group=null;function
__construct($name=__CLASS__){$this->name=$name;}function
shuffle($deep=false){if(count($this->tests)===1||$deep){array_filter($this->tests,'shuffle');}else{shuffle($this->tests);}return$this;}function
run(){foreach($this->tests
as$tests){foreach($tests
as$test){$test->run();}}return$this;}function
__invoke(){return$this->run();}function
getName(){return$this->name;}function
addTest($name,$callback,$type=null){if(is_string($callback)&&is_file(__DIR__.$callback)||is_file($callback)){$callback=Check::file(basename($callback),dirname($callback));}return$this->tests[$this->getGroup()][]=Test::instance($name,$callback,$type);}function
__toString(){$results=\envtesting\App::header($this->name);foreach($this->tests
as$group=>$tests){$results.=implode(PHP_EOL,$tests).PHP_EOL;}return$results.PHP_EOL;}function
addFromDir($dir,$type=''){$iterator=new\RegexIterator(new\RecursiveIteratorIterator(new\RecursiveDirectoryIterator($dir)),'/\.php$/i');foreach($iterator
as$filePath=>$fileInfo){$this->addTest($fileInfo->getBasename('.php'),Check::file($filePath,''))->setType($type);}return$this;}function
offsetExists($offset){return
array_key_exists($offset,$this->tests[$this->getGroup()]);}function
offsetGet($offset){return$this->tests[$this->getGroup()][$offset];}function
offsetSet($offset,$value){if(!$value
instanceof
Test){throw
new\Exception('Usupported test type');}if($offset===null){$this->tests[$this->getGroup()][]=$value;}else{$this->tests[$this->getGroup()][$offset]=$value;}}function
offsetUnset($offset){if(isset($this->tests[$this->getGroup()][$offset])){unset($this->tests[$this->getGroup()][$offset]);}}function
getIterator(){return
new\ArrayIterator($this->tests);}function
__get($name){return$this->to($name);}function
getTests(){}function
to($name){$this->group=$name;return$this;}function
getGroup($name=null){return$this->group?$this->group:'main';}function
getGroups(){return
array_keys($this->tests);}function
hasGroups(){return
count($this->tests)!==1;}static
function
instance($name){return
new
self($name);}}class
Warning
extends\Exception{}}