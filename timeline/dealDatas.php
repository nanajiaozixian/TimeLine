<?php
define('VERSIONS', 'versions');//�������а��ļ����ļ�������
$version_template = "pages".DIRECTORY_SEPARATOR;
global $localfilepath;
if(isset($_POST['pageurl'])){
	$pageURL = $_POST['pageurl'];
	$parts = parse_url($pageURL);//����url
	$host = $parts['host'];//��ȡhostname
	$main_file_init = basename($parts['path']);//��ȡpathname
	$folder_name = preg_replace("/(\w+)\.(\w+)\.(\w+)/i", "$3.$2.$1", $host);
	
	$main_file_init = basename($parts['path']);//��ȡpathname
	$local_file = $main_file_init."_local.html";
	$filepath_v0 = $version_template.$folder_name.DIRECTORY_SEPARATOR.VERSIONS.DIRECTORY_SEPARATOR."v0".DIRECTORY_SEPARATOR.$local_file;
	$localfilepath =  $filepath_v0;
}

?>
