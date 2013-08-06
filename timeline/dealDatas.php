<?php

/******
**���ߣ� Doris
**ʱ�䣺 2013-8-14
**���ã� timeline���ݴ���
******/

define('VERSIONS', 'versions');//�������а��ļ����ļ�������
define('BROWSER_SEPARATOR', '/');

/*
$version_template = "pages".BROWSER_SEPARATOR;
global $localfilepath;


if(isset($_POST['pageurl'])){
	$pageURL = $_POST['pageurl'];
	$parts = parse_url($pageURL);//����url
	$host = $parts['host'];//��ȡhostname
	$main_file_init = basename($parts['path']);//��ȡpathname
	$folder_name = preg_replace("/(\w+)\.(\w+)\.(\w+)/i", "$3.$2.$1", $host);
	$folder_name = $main_file_init.".".$folder_name;
	$local_file = $main_file_init."_local.html";
	$filepath_v0 = $version_template.$folder_name.BROWSER_SEPARATOR.VERSIONS.BROWSER_SEPARATOR."v0".BROWSER_SEPARATOR.$local_file;
	$localfilepath =  "http://localhost/timeline/".$filepath_v0;
	echo $localfilepath;
}
*/



/***************************************************************������*********************************************************************/

/**
**������ WebPageInfor
**������ҳ�ĸ�����Ϣ�Ͳ���
**/
class WebPageInfor{
	public $max_version=0;
	public $min_version=0;
	public $local_file_name;
	public $files_path = array();

}
$myWebPage = new WebPageInfor();
$myWebPage->files_path = array('v0'=>'pages/cn.com.adobe.www/versions/v0/cn_local.html',
																'v1'=>'pages/cn.com.adobe.www/versions/v1/cn_local.html',
																'v2'=>'pages/cn.com.adobe.www/versions/v2/cn_local.html',
																'v3'=>'pages/cn.com.adobe.www/versions/v3/cn_local.html');
$myWebPage->max_version = 3;
$myWebPage->local_file_name ="cn_local.html";

sentJSON($myWebPage);

/**************************************************************���ַ���*********************************************************************/
/**
**�������� getWebPageInfor
**���ã� ��ȡ��ҳ�ĸ�����Ϣ
**var url ��ҳ������
**/
function getWebPageInfor($url){
	//��mongodb��ȡ��ҳ�ĸ�����Ϣ
	
	$page_infor = new WebPageInfor();
	return $page_infor;
}


function sentJSON($page){
	if(get_class($page)!= "WebPageInfor"){
		return;
	}
	$data_arr = array(
		'files_path'=>$page->files_path,
		'max_version'=>$page->max_version,
		'min_version'=>$page->min_version,
		'local_file_name'=>$page->local_file_name
	);
	
	$json_string = json_encode($data_arr);
	echo $json_string;
	
	
}





?>