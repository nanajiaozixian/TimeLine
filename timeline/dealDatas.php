<?php

/******
**作者： Doris
**时间： 2013-8-14
**作用： timeline数据处理
******/

define('VERSIONS', 'versions');//保存所有版文件的文件夹名字
define('BROWSER_SEPARATOR', '/');

/*
$version_template = "pages".BROWSER_SEPARATOR;
global $localfilepath;


if(isset($_POST['pageurl'])){
	$pageURL = $_POST['pageurl'];
	$parts = parse_url($pageURL);//解析url
	$host = $parts['host'];//获取hostname
	$main_file_init = basename($parts['path']);//获取pathname
	$folder_name = preg_replace("/(\w+)\.(\w+)\.(\w+)/i", "$3.$2.$1", $host);
	$folder_name = $main_file_init.".".$folder_name;
	$local_file = $main_file_init."_local.html";
	$filepath_v0 = $version_template.$folder_name.BROWSER_SEPARATOR.VERSIONS.BROWSER_SEPARATOR."v0".BROWSER_SEPARATOR.$local_file;
	$localfilepath =  "http://localhost/timeline/".$filepath_v0;
	echo $localfilepath;
}
*/
/***************************************************************各种类*********************************************************************/

/**
**类名： WebPageInfor
**代表网页的各种信息和操作
**/
class WebPageInfor{
	public $versions = array();
	public $max_version;
	public $min_version;
	public $file_name;
	public $file_path = array();

	public function _construct(){

	}

	public function _destruct(){

	}
}


/**************************************************************各种方法*********************************************************************/
/**
**函数名： getWebPageInfor
**作用： 获取网页的各种信息
**var url 网页的链接
**/
function getWebPageInfor($url){
	//从mongodb获取网页的各种信息

	$page_infor = new WebPageInfor();
	return $page_infor;
}








?>