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
extends\Exception{}class
Filter{public$type=null;public$group=null;public$name=null;function
__construct($name=null,$type=null,$group=null){$this->name=$name;$this->group=$group;$this->type=$type;}function
isActive(){return$this->type!==null||$this->name!==null||$this->group!==null;}function
isValid(Test$test,Suit$suit){return$this->isActive()?($this->name&&$test->getName()===$this->name)||($this->type&&$test->getType()===$this->type)||($this->group&&$suit->getCurrentGroupName()===$this->group):true;}static
function
instanceFromArray(array$array){return
new
self(isset($array['name'])?(string)$array['name']:null,isset($array['type'])?(string)$array['type']:null,isset($array['group'])?(string)$array['group']:null);}}}namespace envtesting\output{use
envtesting\Suit;final
class
Csv{static
function
render(Suit$suit,$title=''){echo'<pre>';foreach($suit
as$group=>$tests){foreach($tests
as$order=>$test){$options=($test->getOptions()?'<br/>'.json_encode($test->getOptions()):'');if($test->isEnabled()){$data=array($test->getStatus(),$group.':'.$test->getName(),$test->getNotice(),$test->getType(),$test->isOk()?'OK':$test->getStatusMessage().$options,$order);echo
implode(', ',$data).PHP_EOL;}}}}}final
class
Html{static
function
render(Suit$suit,$title=''){$total=$error=$warning=$exception=$ok=$disabled=0;$filter=$suit->getFilter();}namespace {?><!DOCTYPE html>
<html lang="en-us" dir="ltr">
<head>
	<meta charset="UTF-8">
	<title><?=$title?></title>
	<meta name="robots" content="noindex, nofollow, noarchive, noodp"/>

	<link rel="stylesheet" type="text/css" media="all"
	      href="//current.bootstrapcdn.com/bootstrap-v204/css/bootstrap-combined.min.css"/>
	<style type="text/css">
		tr.warning {
			background: #F89406;
		}

		tr.disabled, tr.disabled a {
			color: #a0a0a0;
		}

		tr.error, tr.exception {
			background: #BD362F;
		}

		tr.ok {
			background: #5BB75B;
		}

		.modal pre {
			overflow: auto;
			height: 150px;
		}

		body {
			padding-top: 20px;
		}

		.icon-info-sign {
			float: right;
		}

		table a {
			color: #333;
		}

		h3 {
			text-align: center;
			color: #05C;
			padding-bottom: 1em;
		}

		thead {
			border-top: 1px solid #ddd;
		}

		.btn-group {
			padding-bottom: 1em;
		}

		.footer {
			border-top: 1px solid #ddd;
			padding-top: 1em;
		}

	</style>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="span12">
			<h3><?=$title?></h3>

			<?if($filter->isActive()){?>
			<div class="alert">
				<a href="?" class="close">&times;</a>Test results are filtered!
			</div>
			<?}?>

			<table class="table table-condensed">
				<colgroup style="width:25px;"/>
				<colgroup style="width:80px;"/>
				<colgroup style="width:150px;"/>
				<colgroup style="width:150px;"/>
				<colgroup style="width:120px;"/>
				<colgroup style="width:100px;"/>
				<thead>
				<tr>
					<th></th>
					<th title="Warning | Error | Ok or disabled">Result</th>
					<th title="Unique test name">
						Name<?=$filter->name?' = <span class="label label-inverse">'.$filter->name.'</span>':''?>
					</th>
					<th title="Test group">
						Group<?=$filter->group?' = <span class="label label-inverse">'.$filter->group.'</span>':''?>
					</th>
					<th title="Test type">
						Type<?=$filter->type?' = <span class="label label-inverse">'.$filter->type.'</span>':''?>
					</th>
					<th title="Notice eg. stable server">Notice</th>
					<th title="Response message">Message</th>
				</tr>
				</thead>

				<tbody>

				<?foreach($suit
as$group=>$tests){?>
					<?foreach($tests
as$order=>$test){?>
						<?$total++;?>
					<tr class="<?=strtolower($test->getStatus())?>">
						<td>
							<i class="icon-<?=$test->isOk()?'ok':'remove'?>"></i>
						</td>
						<td><?=$test->getStatus();?></td>
						<td><a href="?name=<?=$test->getName();?>"><?=$test->getName();?></a></td>
						<td><a href="?group=<?=$group?>"><?=$group?></a></td>
						<td>
							<a href="?type=<?=$test->getType();?>"><?=$test->getType();?></a>
						</td>
						<td><?=$test->getNotice();?></td>
						<td>
							<?=$test->getStatusMessage();?>
							<?if($test->hasOptions()){?>
							<code>Options: <?=json_encode((array)$test->getOptions());?></code>
							<?}?>
						</td>

					</tr>
						<?if($test->isOk()&&$test->isEnabled())$ok++;?>
						<?if(!$test->isEnabled())$disabled++;?>
						<?if($test->isError())$error++;?>
						<?if($test->isWarning())$warning++;?>
						<?}?>

					<?}$enabled=$total-$disabled;?>
				</tbody>
			</table>

			<div class="btn-group">
				<a href="" class="btn btn-primary">Refresh</a><?if($filter->isActive()){?>
				<a href="?" class="btn btn-danger">Cancel filter</a><?}?>
			</div>

			<div class="footer">
				<?if($disabled>0){?>
				<span class="badge badge-info"><?=$disabled?> DISABLED <?=round(100*$disabled/$total)?>%</span>
				<?}?>
				<?if($error>0){?>
				<span class="badge badge-important"><?=$error?> ERROR <?=round(100*$error/$enabled)?>%</span>
				<?}?>
				<?if($warning>0){?>
				<span class="badge badge-warning"><?=$warning?> WARNING <?=round(100*$warning/$enabled)?>%</span>
				<?}?>
				<span class="badge badge-success"><?=$ok?> OK <?=round(100*$ok/($total-$disabled))?>%</span>
				<span class="badge badge-inverse"><?=$total?> TESTS</span>
				<a data-toggle="modal" href="#about" class="icon-info-sign"></a>
			</div>

		</div>
	</div>


	<div class="row">

		<div class="modal hide" id="about">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Envtesting</h3>
			</div>
			<div class="modal-body">
				<p>Envtesting is fast simple and <strong>easy to use</strong> environment testing written in PHP.
					Can check library, services and services response. Produce console, HTML or CSV output.
				</p>

				<h4>Authors</h4>

				<p>
					<a href="http://www.wikidi.com">wikidi.com</a> +
					<a href="https://twitter.com/#!/OzzyCzech" title="Roman Ožana" target="_blank">@OzzyCzech</a>
				</p>

				<h4>Copyright & License</h4>

				<pre><??>Copyright (c) 2012, Envtesting (Roman Ozana <ozana@omdesign.cz>) All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
    * Neither the name of the Morphine nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

</pre>

			</div>

			<div class="modal-footer">
				<a href="https://github.com/wikidi/envtesting" class="btn btn-primary" target="blank">Fork me on GitHub</a>
				<a href="#" class="btn btn-success" data-dismiss="modal">Thanks!</a>
			</div>
		</div>

	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="//current.bootstrapcdn.com/bootstrap-v204/js/bootstrap.min.js"></script>
</body>
<!--
	Generated at <?=date('j.n.Y H:i:s')?> by Envtesting
	https://github.com/wikidi/envtesting
-->
</html><?php }namespace envtesting\output{}}}namespace envtesting{class
Suit
implements\ArrayAccess,\IteratorAggregate{protected$groups=array();protected$name=__CLASS__;protected$currentGroup=null;protected$failGroupOnFirstError=false;protected$filter=null;function
__construct($name=__CLASS__,Filter$filter=null){$this->name=$name;$this->filter=($filter)?$filter:new
Filter();}function
run(){foreach($this->groups
as$tests){$isError=false;foreach($tests
as$test){$test->run();$isError=$test->isError()&&$this->failGroupOnFirstError;}}return$this;}function
shuffle($deep=false){if($deep||$this->hasGroups()===false)array_filter($this->groups,'shuffle');if($this->hasGroups()){$keys=array_keys($this->groups);shuffle($keys);$this->groups=array_merge(array_flip($keys),$this->groups);}return$this;}function
addTest($name,$callback,$type=null){if(is_string($callback)&&(is_file(__DIR__.$callback)||is_file($callback))){$callback=Check::file(basename($callback),dirname($callback));}$test=Test::instance($name,$callback,$type);$test->enable($this->filter->isValid($test,$this));return$this->groups[$this->getCurrentGroupName()][]=$test;}function
addFromDir($dir,$type=''){$iterator=new\RegexIterator(new\RecursiveIteratorIterator(new\RecursiveDirectoryIterator($dir)),'/\.php$/i');foreach($iterator
as$filePath=>$fileInfo){$this->addTest($fileInfo->getBasename('.php'),$filePath,$type);}return$this;}function
__get($name){return$this->to($name);}function
to($name){$this->currentGroup=$name;if(!array_key_exists($name,$this->groups))$this->groups[$name]=array();return$this;}function
getName(){return$this->name;}function
setName($name){$this->name=$name;return$this;}function
getCurrentGroupName(){return$this->currentGroup?$this->currentGroup:'main';}function
getGroupsNames(){return
array_keys($this->groups);}function
hasGroups(){return
count($this->groups)>1;}function
setFilter(Filter$filter){$this->filter=$filter;return$this;}function
getFilter(){return$this->filter;}function
failGroupOnFirstError($fail=true){$this->failGroupOnFirstError=$fail;return$this;}static
function
instance($name,Filter$filter=null){return
new
self($name,$filter);}function
offsetExists($offset){return
array_key_exists($offset,$this->groups[$this->getCurrentGroupName()]);}function
offsetGet($offset){return$this->offsetExists($offset)?$this->groups[$this->getCurrentGroupName()][$offset]:null;}function
offsetSet($offset,$value){if(!$value
instanceof
Test){throw
new\Exception('Usupported test type');}if($offset===null){$this->groups[$this->getCurrentGroupName()][]=$value;}else{$this->groups[$this->getCurrentGroupName()][$offset]=$value;}}function
offsetUnset($offset){if(isset($this->groups[$this->getCurrentGroupName()][$offset])){unset($this->groups[$this->getCurrentGroupName()][$offset]);}}function
getIterator(){return
new\ArrayIterator($this->groups);}function
__toString(){$results=\envtesting\App::header($this->name);foreach($this->groups
as$group=>$tests){$results.=implode(PHP_EOL,$tests).PHP_EOL;}return$results.PHP_EOL;}}class
Test{protected$name='';protected$callback=null;protected$type=null;protected$options=array();protected$notice='';protected$result=null;protected$enabled=true;function
__construct($name,$callback,$type=null,$enabled=true){$this->name=$name;$this->callback=$callback;$this->type=$type;$this->enabled=$enabled;}function
withOptions(){$this->options=func_get_args();return$this;}function
run(){try{$this->result='OK';call_user_func_array($this->getCallback(),$this->getOptions());}catch(Error$error){$this->setResult($error);}catch(Warning$warning){$this->setResult($warning);}catch(\Exception$e){$this->setResult($e);}return$this;}function
__invoke(){return$this->run();}function
getStatus(){if(is_scalar($this->getResult()))return(string)$this->getResult();if(!$this->enabled)return'DISABLED';if($this->isError())return'ERROR';if($this->isWarning())return'WARNING';if($this->isException())return'EXCEPTION';throw
new\Exception('Invalid result type: '.gettype($this->result));}function
getStatusMessage(){return($this->result
instanceof\Exception)?$this->result->getMessage():'';}function
isWarning(){return$this->getResult()instanceof
Warning;}function
isError(){return$this->getResult()instanceof
Error;}function
isOk(){return!$this->isException();}function
isException(){return$this->getResult()instanceof\Exception;}function
getResult(){if($this->result===null&&$this->enabled)$this->run();return$this->result;}function
setResult($result){$this->result=$result;return$this;}function
__toString(){$response=array('status'=>str_pad($this->getStatus(),10,' '),'name'=>str_pad($this->getName(),20,' '),'type'=>str_pad($this->getType(),10,' '),'options'=>$this->hasOptions()?json_encode((object)$this->getOptions()):'','notice'=>$this->getNotice(),'message'=>$this->getStatusMessage());return
implode($response,' | ');}function
getCallback(){if(is_callable($this->callback))return$this->callback;if(is_string($this->callback)&&strpos($this->callback,'::')){return$this->callback=explode('::',$this->callback);}throw
new\Exception('Invalid callback');}function
setName($name=''){$this->name=$name;return$this;}function
getName(){return$this->name;}function
getType(){return$this->type;}function
setType($type){$this->type=$type;return$this;}function
getOptions(){return$this->options;}function
hasOptions(){return!empty($this->options);}function
setOptions(array$options=array()){$this->options=$options;return$this;}function
getNotice(){return$this->notice;}function
setNotice($notice=''){$this->notice=$notice;return$this;}function
enable($enabled=true){$this->enabled=$enabled;return$this;}function
isEnabled(){return$this->enabled;}function
isDisabled(){return!$this->isEnabled();}static
function
instance($name,$callback,$type=null,$enabled=true){return
new
self($name,$callback,$type,$enabled);}}class
Warning
extends\Exception{}}