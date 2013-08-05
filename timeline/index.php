<?php

/******
**作者： Doris
**时间： 2013-8-14
**作用： 实现wartisan产品的timeline功能
******/


require_once('dealDatas.php');

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
	<script>
		$(function(){
			$().timelinr({
				orientation: 	'vertical',
				issuesSpeed: 	300,
				datesSpeed: 	100,
				arrowKeys: 		'true',
				startAt:		3
			})
		});
	</script>
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
	<div id="timeline">
		<ul id="dates">
			<li><a href="#V1" class="selected">V1</a></li>
			<li><a href="#V2">V2</a></li>
			<li><a href="#V3">V3</a></li>
			<li><a href="#V4">V4</a></li>
			<li><a href="#V5">V5</a></li>
			<li><a href="#V6">V6</a></li>
			<li><a href="#V7">V7</a></li>
			<li><a href="#V8">V8</a></li>
			<li><a href="#V9">V9</a></li>
			<li><a href="#V10">V10</a></li>
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