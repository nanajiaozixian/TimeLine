<?php

/******
**作者： Doris
**时间： 2013-8-14
**作用： 实现wartisan产品的timeline功能
******/


//require_once('dealDatas.php');

$localfilepath = "";
UIHtml();

//显示timeline界面
function UIHtml(){
?>
<html>
 <head>
  <title> Timeline Demo </title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <script src="js/jquery-2.0.3.min.js"></script>
  <script src="js/jquery.timelinr-0.9.53.js"></script>
  <script src="js/index.js"></script>

  <link rel="stylesheet" href="css/style_v.css" media="screen" />
  <style>
	#webpage{
	display: none;
	width: 85%;
	height: 800px;
	float: right;
	}
	#right{
	width: 85%;
	height: 800px;
	float: right;
	}
	#leftbar{
	width: 10%;
	float: left;
	}
  </style>
 </head>

 <body>
  <div id="leftbar">
	<div id="url">Page URL:
		<input id="addr" name="pageurl" type="text" /> 
		<input id="show" name="show" type="submit" value="Show"/>
	</div>
	<div>Version:
		<input id="vers" name="version" type="text" /> 
		<button id="snap">Snapshot</button>
	</div>
	<div id="timeline">
		<ul id="dates">
		</ul>
	</div><!--end timeline-->
  </div><!--end leftbar-->
  <div id="right">
	<iframe id="webpage" name="webpage" src=""></iframe>
  </div>
 </body>
</html>
<?php
}

?>