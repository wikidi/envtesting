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
envtesting\Suit;final
class
Html{static
function
render(Suit$suit,$title=''){?><!DOCTYPE html>
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

	<?foreach($suit
as$group){?>
		<?foreach($group
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
</html><?php }}}namespace envtesting{class
Suit
implements\ArrayAccess,\IteratorAggregate{protected$groups=array();protected$name=__CLASS__;protected$currentGroup=null;protected$failGroupOnFirstError=false;function
__construct($name=__CLASS__){$this->name=$name;}function
run(){foreach($this->groups
as$tests){$isError=false;foreach($tests
as$test){$test->run();$isError=$test->isError()&&$this->failGroupOnFirstError;}}return$this;}function
shuffle($deep=false){if($this->hasGroups()===false||$deep){array_filter($this->groups,'shuffle');}else{shuffle($this->groups);}return$this;}function
addTest($name,$callback,$type=null){if(is_string($callback)&&(is_file(__DIR__.$callback)||is_file($callback))){$callback=Check::file(basename($callback),dirname($callback));}return$this->groups[$this->getCurrentGroupName()][]=Test::instance($name,$callback,$type);}function
addFromDir($dir,$type=''){$iterator=new\RegexIterator(new\RecursiveIteratorIterator(new\RecursiveDirectoryIterator($dir)),'/\.php$/i');foreach($iterator
as$filePath=>$fileInfo){$this->addTest($fileInfo->getBasename('.php'),$filePath)->setType($type);}return$this;}function
__get($name){return$this->to($name);}function
to($name){$this->currentGroup=$name;return$this;}function
getName(){return$this->name;}function
setName($name){$this->name=$name;return$this;}function
getCurrentGroupName(){return$this->currentGroup?$this->currentGroup:'main';}function
getGroupsNames(){return
array_keys($this->groups);}function
hasGroups(){return
count($this->groups)!==1;}function
failGroupOnFirstError($fail=true){$this->failGroupOnFirstError=$fail;return$this;}static
function
instance($name){return
new
self($name);}function
offsetExists($offset){return
array_key_exists($offset,$this->groups[$this->getCurrentGroupName()]);}function
offsetGet($offset){return$this->groups[$this->getCurrentGroupName()][$offset];}function
offsetSet($offset,$value){if(!$value
instanceof
Test){throw
new\Exception('Usupported test type');}if($offset===null){$this->groups[$this->getCurrentGroupName()][]=$value;}else{$this->groups[$this->getCurrentGroupName()][$offset]=$value;}}function
offsetUnset($offset){if(isset($this->groups[$this->getCurrentGroupName()][$offset])){unset($this->groups[$this->getCurrentGroupName()][$offset]);}}function
getIterator(){return
new\ArrayIterator($this->groups);}function
__toString(){$results=\envtesting\App::header($this->name);foreach($this->groups
as$group=>$tests){$results.=implode(PHP_EOL,$tests).PHP_EOL;}return$results.PHP_EOL;}}class
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
setOptions(array$options){$this->options=$options;return$this;}function
getNotice(){return$this->notice;}function
setNotice($notice){$this->notice=$notice;return$this;}static
function
instance($name,$callback,$type=null){return
new
self($name,$callback,$type);}}class
Warning
extends\Exception{}}