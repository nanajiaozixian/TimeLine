<?php
define('VERSIONS', 'versions');//保存所有版文件的文件夹名字
define('BROWSER_SEPARATOR', '/');
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


/***************************************************************类定义*********************************************************************/
class WebPageInfor{
	
}
?>
