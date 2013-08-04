<?php
define('VERSIONS', 'versions');//保存所有版文件的文件夹名字
$version_template = "pages".DIRECTORY_SEPARATOR;
global $localfilepath;
if(isset($_POST['pageurl'])){
	$pageURL = $_POST['pageurl'];
	$parts = parse_url($pageURL);//解析url
	$host = $parts['host'];//获取hostname
	$main_file_init = basename($parts['path']);//获取pathname
	$folder_name = preg_replace("/(\w+)\.(\w+)\.(\w+)/i", "$3.$2.$1", $host);
	
	$main_file_init = basename($parts['path']);//获取pathname
	$local_file = $main_file_init."_local.html";
	$filepath_v0 = $version_template.$folder_name.DIRECTORY_SEPARATOR.VERSIONS.DIRECTORY_SEPARATOR."v0".DIRECTORY_SEPARATOR.$local_file;
	$localfilepath =  $filepath_v0;
}

?>
