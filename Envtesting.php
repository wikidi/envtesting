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
Error($message);}}}class
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
function()use($file,$dir){ob_start(); include $dir . DIRECTORY_SEPARATOR . $file;return
ob_get_clean();};}}class
Error
extends\Exception{}class
Warning
extends\Exception{}class
Filter{public$type=null;public$group=null;public$name=null;function
__construct($name=null,$type=null,$group=null){$this->name=$name;$this->group=$group;$this->type=$type;}function
isActive(){return$this->type!==null||$this->name!==null||$this->group!==null;}function
isValid(Test$test,Suite$suite){return$this->isActive()?($this->name&&$test->getName()===$this->name)||($this->type&&$test->getType()===$this->type)||($this->group&&$suite->getCurrentGroupName()===$this->group):true;}static
function
instanceFromArray(array$array){return
new
self(isset($array['name'])?(string)$array['name']:null,isset($array['type'])?(string)$array['type']:null,isset($array['group'])?(string)$array['group']:null);}}}namespace envtesting\output{use
envtesting\Suite;final
class
Csv{static
function
render(Suite$suite){$name=preg_replace('#[^a-z0-9]+#i','-',strtolower($suite->getName()));header('Content-type: text/csv');header('Content-Disposition: attachment; filename='.trim($name,'-').'.env.csv');header('Pragma: no-cache');header('Expires: 0');foreach($suite
as$group=>$tests){foreach($tests
as$order=>$test){$message=is_scalar($message=$test->getStatusMessage())?$message:json_encode($message);$options=($test->getOptions()?'<br/>'.json_encode($test->getOptions()):'');if($test->isEnabled()){$data=array($test->getStatus(),$group.':'.$test->getName(),$test->getNotice(),$test->getType(),$test->isOk()?'OK':preg_replace('/\s+/i',' ',trim($message.$options)),$order);echo
addslashes(implode(', ',$data)).PHP_EOL;}}}}}final
class
Html{static
function
render(Suite$suite){$total=$error=$warning=$exception=$ok=$disabled=0;$filter=$suite->getFilter();?><!DOCTYPE html>
<html lang="en-us" dir="ltr">
<head>
	<meta charset="UTF-8">
	<title><?=$suite->getName()?></title>
	<meta name="robots" content="noindex, nofollow, noarchive, noodp"/>

	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">

	<style type="text/css">
		.table>tbody>tr.disabled>td,
		.table>tbody>tr.disabled>td>a {
			background-color: #cfcfcf;
			border-color: rgba(255, 255, 255, .3);
			color: #555;
		}

		.table>thead>tr>th {
			border: 0;
		}

		.table>tbody>tr.warning>td,
		.table>tbody>tr.warning>td>a {
			background-color: #F89406;
			border-color: rgba(255, 255, 255, .3);
			color: #593503;
		}

		.table>tbody>tr.danger>td,
		.table>tbody>tr.danger>td>a,
		.table>tbody>tr.exception>td,
		.table>tbody>tr.exception>td>a {
			background-color: #BD362F;
			border-color: rgba(255, 255, 255, .3);
			color: #661c1a;
		}

		.table>tbody>tr.success>td,
		.table>tbody>tr.success>td>a {
			background: #5BB75B;
			border-color: rgba(255, 255, 255, .3);
			color: #204020;
		}

		.modal pre {
			overflow: auto;
			height: 150px;
		}

		.glyphicon-question-sign {
			float: right;
			font-size: 18px;
			color: #555;
		}

		table a, table a:hover {
			color: #333;
		}

		footer {
			padding-top: 1em;
			padding-bottom: 4em;
		}

		footer .label {
			margin-right: 5px;
		}

	</style>
</head>
<body>
<div class="container">
	<header class="row">
		<h3>Envtesting<?=$suite->getName()?' : '.$suite->getName():null;?></h3>

		<?if($filter->isActive()){?>
			<div class="alert alert-info">
				<a href="<?=Html::link()?>" class="close">&times;</a>Test results are filtered!
			</div>
		<?}?>
	</header>

	<div class="row">
		<table class="table table-condensed ">
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
					Name<?=$filter->name?' = <span class="label label-default">'.$filter->name.'</span>':''?>
				</th>
				<th title="Test group">
					Group<?=$filter->group?' = <span class="label label-default">'.$filter->group.'</span>':''?>
				</th>
				<th title="Test type">
					Type<?=$filter->type?' = <span class="label label-default">'.$filter->type.'</span>':''?>
				</th>
				<th title="Notice eg. stable server">Notice</th>
				<th title="Response message">Message</th>
			</tr>
			</thead>

			<tbody>
			<?$total=$ok=$disabled=$error=$warning=0;?>
			<?foreach($suite
as$group=>$tests){?>
				<?foreach($tests
as$order=>$test){?>
					<?$total++;?>
					<tr class="<?=Html::getStatusAsClass($test->getStatus())?>">
						<td>
							<i class="icon-<?=$test->isOk()?'ok':'remove'?>"></i>
						</td>
						<td><?=$test->getStatus();?></td>
						<td><a href="<?=Html::link('name='.$test->getName())?>"><?=$test->getName();?></a></td>
						<td><a href="<?=Html::link('group='.$group);?>"><?=$group?></a></td>
						<td>
							<a href="<?=Html::link('type='.$test->getType())?>"><?=$test->getType();?></a>
						</td>
						<td><?=$test->getNotice();?></td>
						<td>
							<?=$test->getStatusMessage(true)?>
							<?if($test->hasOptions()){?>
								<br><code>Options: <?=json_encode((array)$test->getOptions());?></code>
							<?}?>
						</td>

					</tr>
					<?if($test->isOk()&&$test->isEnabled())$ok++;?>
					<?if(!$test->isEnabled())$disabled++;?>
					<?if($test->isError())$error++;?>
					<?if($test->isWarning())$warning++;?>
				<?}?>

			<?}?>
			<?$enabled=$total-$disabled;?>
			</tbody>
		</table>


		<div class="btn-group">
			<a href="<?=Html::link(null,true)?>" class="btn btn-primary" title="Refresh current tests"><i
					class="icon-refresh icon-white"></i> Refresh</a>
			<a href="<?=Html::link('output=csv',true)?>" class="btn btn-default" title="Download CSV output">CSV <i
					class="icon-arrow-down"></i></a>
			<?if($filter->isActive()){?>
				<a href="<?=Html::link(null)?>" class="btn btn-danger" title="Cancel filter">Cancel filter</a>
			<?}?>
		</div>
	</div>

	<footer class="row">
		<?if($disabled>0){?>
			<span class="label label-default">
					<?=$disabled?> DISABLED <?=$total?round(100*$disabled/$total):0?>%
				</span>
		<?}?>
		<?if($error>0){?>
			<span class="label label-danger">
					<?=$error?> ERROR <?=$enabled?round(100*$error/$enabled):0?>%
				</span>
		<?}?>
		<?if($warning>0){?>
			<span class="label label-warning">
					<?=$warning?> WARNING <?=$enabled?round(100*$warning/$enabled):0?>%
				</span>
		<?}?>
		<span class="label label-success">
					<?=$ok?> OK <?=($total-$disabled)?round(100*$ok/($total-$disabled)):0?>%
				</span>
		<span class="label label-default"><?=$total?> TESTS</span>
		<a data-toggle="modal" href="#about" class="glyphicon glyphicon-question-sign"></a>
	</footer>


	<div class="modal" id="about" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
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
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
<!--
	Generated at <?=date('j.n.Y H:i:s')?> by Envtesting
	https://github.com/wikidi/envtesting
-->
</html>
<?php }static
function
link($query=null,$add=false){$url=isset($_SERVER['REQUEST_URI'])?'/'.trim(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH),'/'):'/';if($add&&isset($_SERVER['QUERY_STRING']))$query.='&'.$_SERVER['QUERY_STRING'];parse_str($query,$params);return($params)?$url.'?'.http_build_query($params):$url;}static
function
getStatusAsClass($status){$statuses=array('CRIT'=>'danger','OK'=>'success','DISABLED'=>'disabled','WARNING'=>'warning','EXCEPTION'=>'danger exception');return$statuses[$status];}}}namespace envtesting{class
Suite
implements\ArrayAccess,\IteratorAggregate{private
static$instance=null;protected$groups=array();protected$name=null;protected$currentGroup=null;protected$failGroupOnFirstError=false;protected$filter=null;protected$run=false;function
__construct($name=__CLASS__,Filter$filter=null){$this->name=$name;$this->filter=($filter)?$filter:Filter::instanceFromArray($_GET);}function
run(){foreach($this->groups
as$tests){$failed=false;foreach($tests
as$test){if($failed){$test->setResult($failed->getResult());$test->setNotice($failed->getNotice());$test->setOptions($failed->getOptions());}else{$test->run();$failed=($test->isFail()&&$this->failGroupOnFirstError)?$test:false;}}}$this->run=true;return$this;}function
shuffle($deep=false){if($deep||$this->hasGroups()===false)array_filter($this->groups,'shuffle');if($this->hasGroups()){$keys=array_keys($this->groups);shuffle($keys);$this->groups=array_merge(array_flip($keys),$this->groups);}return$this;}function
addTest($name,$callback,$type=null){if(is_string($callback)&&(is_file(__DIR__.$callback)||is_file($callback))){$callback=Check::file(basename($callback),dirname($callback));}$test=new
Test($name,$callback,$type);$test->enable($this->filter->isValid($test,$this));return$this->groups[$this->getCurrentGroupName()][]=$test;}function
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
instance($name=null,Filter$filter=null){if(self::$instance==null)self::$instance=new
self($name,$filter);return
self::$instance;}function
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
__toString(){if($this->run===false)return'Call ->run() before try getting results'.PHP_EOL;$results=\envtesting\App::header($this->name);foreach($this->groups
as$group=>$tests){$results.=implode(PHP_EOL,$tests).PHP_EOL;}return$results.PHP_EOL;}function
render($to=null){if($this->run===false)throw
new\Exception('Call run before try getting results');if($to===null&&isset($_GET['output'])){$to=$_GET['output']==='csv'?'csv':'html';}elseif($to===null&&PHP_SAPI==='cli'){$to='cli';}switch($to){case'cli':echo$this;break;case'csv':echo\envtesting\output\Csv::render($this);break;case'html':default:echo\envtesting\output\Html::render($this);break;}}}class
Test{protected$name='';protected$callback=null;protected$callResponse=null;protected$type=null;protected$options=array();protected$notice='';protected$result=null;protected$enabled=true;function
__construct($name,$callback,$type=null,$enabled=true){$this->name=$name;$this->callback=$callback;$this->type=$type;$this->enabled=$enabled;}function
withOptions(){$this->options=func_get_args();return$this;}function
run(){if(!$this->enabled)return$this;try{$this->setResult('OK');$this->callResponse=$result=call_user_func_array($this->getCallback(),$this->getOptions());}catch(Error$error){$this->setResult($error);}catch(Warning$warning){$this->setResult($warning);}catch(\Exception$e){$this->setResult($e);}if($this->callResponse
instanceof\Exception){$this->setResult($result);}return$this;}function
__invoke(){return$this->run();}function
getStatus(){if(is_scalar($this->getResult()))return(string)$this->getResult();if($this->isDisabled())return'DISABLED';if($this->isError())return'CRIT';if($this->isWarning())return'WARNING';if($this->isException())return'EXCEPTION';throw
new\Exception('Invalid result type: '.gettype($this->result));}function
getStatusMessage($html=false,$null=false){if($this->isDisabled())return
null;$message=(is_object($this->callback)&&method_exists($this->callback,'__toString'))?(string)$this->callback:$this->callResponse;$message=($this->result
instanceof\Exception)?$this->result->getMessage():$message;if(($null&&$message===null)||is_bool($message)){return
var_export($message);}elseif(is_scalar($message)){return$html?nl2br($message):$message;}else{return($html?'<code>':null).json_encode($message).($html?'</code>':null);}}function
isWarning(){return$this->getResult()instanceof
Warning;}function
isError(){return$this->getResult()instanceof
Error;}function
isOk(){return!$this->isException();}function
isFail(){return$this->isError()||$this->isException();}function
isException(){return$this->getResult()instanceof\Exception;}function
getResult(){return$this->result;}function
setResult($result){$this->result=$result;return$this;}function
__toString(){$response=array('status'=>str_pad($this->getStatus(),10,' '),'name'=>str_pad($this->getName(),20,' '),'type'=>str_pad($this->getType(),10,' '),'options'=>$this->hasOptions()?json_encode((object)$this->getOptions()):'','notice'=>$this->getNotice(),'message'=>$this->getStatusMessage());return
implode($response,' | ');}function
getCallback(){if(is_callable($this->callback))return$this->callback;if(is_string($this->callback)&&strpos($this->callback,'::')){return$this->callback=explode('::',$this->callback);}if(basename($this->callback)&&!file_exists($this->callback)){throw
new\Exception('Invalid callback: test file not found '.PHP_EOL.$this->callback);}throw
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
isDisabled(){return!$this->enabled;}}class
Throws{static
function
allErrors(){set_error_handler(array('\\envtesting\\Throws','handleError'),E_ALL&~E_DEPRECATED);}static
function
handleError($code,$text,$file,$line,$context){if(error_reporting()==0)return;throw
new\Exception($text.' in file '.$file.' on line '.$line,$code);}static
function
nothing(){restore_error_handler();}}}