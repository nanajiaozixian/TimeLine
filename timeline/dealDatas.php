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








?>