<?php
/***
**作者： Doris
**日期： 2013-8-3
**作用： 下载网页
**		 所有网页内容都会保存在pages文件夹里。
**       下载下来的主页存在各自网页文件夹的versions文件夹里，XX.html是主页面，其他css、js、img文件存在others文件夹里。
**       XX_local.html是XX.html的修改版，文件里的所有路径都改成指向本地的文件的相对路径
**		 目前的文件夹层级结构是：pages/com.adobe.com/versions/v0/others/
**		 如果需要修改保存路径的层级结构, 可以考虑修改VERSIONS、 OTHERS、 V、 $folder_name 、$version、 $others变量
***/


/******************************************************************主要部分*******************************************************************************************/

require_once("wrMongodb.php");

/**宏变量**/
define('VERSIONS', 'versions');//保存所有版文件的文件夹名字
define('OTHERS', 'others');//保存其它文件的文件夹名字
define('V', 'v');//保存单一版本文件的文件夹名字
define('TEMP', 'temporary');//保存临时文件
define('READ_LEN', 4096);
define('BROWSER_SEPARATOR', '/');
 //DIRECTORY_SEPARATOR  路径'/'  
set_time_limit(120);
$v = 0;//版本号 
$url = "";//网页url
 
//获取版本
if(isset($_POST['version'])){
	$v = $_POST['version'];
}else{
	$v = 0;//版本号 
}

//获取网页url
if(isset($_POST['pageurl'])){
	$url = $_POST['pageurl'];
}else{
	$url = "";
}
//$url = "http://www.adobe.com/products/dreamweaver.html?promoid=KAUCF";
//判断url的有效性
if(get_headers($url)===false){
	echo "error";
	return;
}

/**全局变量**/

//网页url ！！！！！！！！！！注意，在整合代码时，这个变量应该是从前端传来的。
$parts = parse_url($url);//解析url
$host = $parts['host'];//获取hostname
$main_file_init = basename($parts['path']);//获取pathname
$folder_name = preg_replace("/(\w+)\.(\w+)\.(\w+)/i", "$3.$2.$1", $host);
$folder_name = $main_file_init.".".$folder_name;//网页的总文件夹名字，根据域名定义，如www.adobe.com/cn,则文件夹名字为cn.com.adobe.com
$version_template = "pages".DIRECTORY_SEPARATOR.$folder_name.DIRECTORY_SEPARATOR.VERSIONS.DIRECTORY_SEPARATOR.V;
$version = $version_template.$v; //version路径: versions\v0 
$others = $version.DIRECTORY_SEPARATOR.OTHERS; //others路径: versions\v0\others

//建version文件夹
createFolder($others);
createFolder(TEMP);

$main_file = $main_file_init;
if(substr($main_file, -5)!=".html"){
	$main_file = $main_file.".html";
}
$local_file = $main_file_init."_local.html";
$str_file = file_get_contents($url);
file_put_contents($version.DIRECTORY_SEPARATOR.$main_file, $str_file);
$verpagepath_local = $version.DIRECTORY_SEPARATOR.$local_file;// html的local文件储存的地址
saveFiles($str_file);
addToDB();
/********************************************************************各种函数************************************************************************************/

/**
*函数名： saveFiles
*作用：从str中提取所有的css，js，图片文件路径并下载
*var: str  查找的源文件
*return: 所有文件的路径
**/
function saveFiles($str){
	$str_new = saveCSSFiles($str);
	$str_new = saveJSFiles($str_new);
	$str_new = saveIMGFiles($str_new);
	$str_new = changeALink($str_new);
	global $verpagepath_local;
	file_put_contents($verpagepath_local, $str_new);
	recursive_delete(TEMP.DIRECTORY_SEPARATOR);//删除临时文件夹里的文件
}

/**
*函数名： createFolder
*作用：创建多层路径
**/
function createFolder($path)
{
   if (!file_exists($path))
   {
    createFolder(dirname($path));

    mkdir($path, 0777);
   }
}

/**
**函数名：isFileExist
**查找旧版本中某文件是否存在
**var $filename: 文件名
**返回值  存在则返回旧版本号，不存在返回false;
**/
function isFileExist($filename){
	global $version_template;
	global $v;
	$old_v = $v-1;
	for(;$old_v>=0; $old_v--){
		$temppath = $version_template.$old_v.DIRECTORY_SEPARATOR.OTHERS.DIRECTORY_SEPARATOR.$filename;
		
		if(file_exists($temppath)){
			return $old_v;
		}
	}
	return false;
}


/**
**函数名：saveCSSFiles
**存储css文件原来的地址、文件名和下载在本地的路径
**var $str: 文件文本
**返回值  存在则修改过路径的文本；
**/
function saveCSSFiles($str){
	global $host;
	global $others;
	global $version;
	global $version_template;
	$localpath = OTHERS.BROWSER_SEPARATOR;
	$arr_link_css = array(); //保存css 文件完整link
	$arr_filename_css = array(); //保存css 文件的名字
	$arr_localpath_css = array();//保存css 文件本地存储路径
	preg_match_all("/<link\s+.*?href=[\"|']([^\"']*)[\"|'].*?>/",$str,$links, PREG_SET_ORDER);//links 里保存了从页面获取的所有css文件的路径
	$count = 0;	
	foreach($links as $val){	
		if(strpos($val[1], "http:")!==0 && substr($val[1], 0,1)!=="/"){		
			continue;
		}
		$arr_link_css[$count] = $val[1];
		if(strpos($val[1], "http:")!==0){
			
			$val[1] = $links[$count][1] = "http://".$host.$val[1];
		}	
		$parts_css = parse_url($val[1]);
		$filname_css = basename($parts_css['path']);//获取pathname
		$arr_filename_css[$count] = $filname_css;
		//判断链接有效性
		if(get_headers($val[1])!==false){		
				$str_file_content = file_get_contents($val[1]);
    		$newfilepath = $version.DIRECTORY_SEPARATOR.$localpath.$filname_css;
    		$arr_localpath_css[$count] = $localpath.$filname_css;
    		
    		//如果旧版本中不存在该文件，则直接下载该文件
    		$old_version = isFileExist($filname_css);
    		$oldfilepath = "";
    		if($old_version === false){
    			file_put_contents($newfilepath, $str_file_content);
    		}else{
    			$oldfilepath = $version_template.$old_version.DIRECTORY_SEPARATOR.OTHERS.DIRECTORY_SEPARATOR.$filname_css;
    			$tempfilepath = TEMP.DIRECTORY_SEPARATOR.$filname_css;
    			file_put_contents($tempfilepath, $str_file_content);
    			if(!compare($oldfilepath, $tempfilepath)){
    				file_put_contents($newfilepath, $str_file_content);
    			}else{
    				$arr_localpath_css[$count] = "..".BROWSER_SEPARATOR.V.$old_version.BROWSER_SEPARATOR.OTHERS.BROWSER_SEPARATOR.$filname_css;
    				
    			}
    		}
		}
		
		$count++;
	}
	
	//把html文件里的css路径更改指向保存的路径
	$str_new = $str;
	$str_new = str_replace($arr_link_css, $arr_localpath_css, $str_new);
	return $str_new;
}

/**
**函数名：saveJSFiles
**存储js文件原来的地址、文件名和下载在本地的路径
**var $str: 文件文本
**返回值  存在则修改过路径的文本；
**/
function saveJSFiles($str){
	global $host;
	global $others;
	global $version;
	global $version_template;
	$localpath = OTHERS.BROWSER_SEPARATOR;
	$arr_link_js = array(); //保存js 文件完整link
	$arr_filename_js = array(); //保存js 文件的名字
	$arr_localpath_js = array();//保存js 文件本地存储路径
	$count = 0;	


	preg_match_all("/<script\s+.*?src=[\"|']([^\"']*)[\"|'].*?>/",$str,$scripts, PREG_SET_ORDER);//scripts 里保存了从页面获取的所有js文件的路径
	//存储js文件原来的地址、文件名和下载在本地的路径
	
	foreach($scripts as $val){	
		if(strpos($val[1], "http:")!==0 && substr($val[1], 0,1)!=="/"){		
			continue;
		}
		$arr_link_js[$count] = $val[1];
		if(strpos($val[1], "http:")!==0){
			
			$val[1] = $scripts[$count][1] = "http://".$host.$val[1];
		}	
		$parts_js = parse_url($val[1]);
		$filname_js = basename($parts_js['path']);//获取pathname
		$arr_filename_js[$count] = $filname_js;
		//判断链接有效性
		if(get_headers($val[1])!==false){		
				$str_file_content = file_get_contents($val[1]);
    		$newfilepath = $version.DIRECTORY_SEPARATOR.$localpath.$filname_js;
    		$arr_localpath_js[$count] = $localpath.$filname_js;
    
    		//如果旧版本中不存在该文件，则直接下载该文件
    		$old_version = isFileExist($filname_js);	
    		$oldfilepath = "";
    		if($old_version === false){
    			file_put_contents($newfilepath, $str_file_content);
    		}else{
    			$oldfilepath = $version_template.$old_version.DIRECTORY_SEPARATOR.OTHERS.DIRECTORY_SEPARATOR.$filname_js;
    			$tempfilepath = TEMP.DIRECTORY_SEPARATOR.$filname_js;
    			file_put_contents($tempfilepath, $str_file_content);
    			if(!compare($oldfilepath, $tempfilepath)){
    				file_put_contents($newfilepath, $str_file_content);
    			}else{
    				$arr_localpath_js[$count] = "..".BROWSER_SEPARATOR.V.$old_version.BROWSER_SEPARATOR.OTHERS.BROWSER_SEPARATOR.$filname_js;
    				
    			}
    		}
		}
		
		$count++;
	}

	//把html文件里的js路径更改指向保存的路径
	$str_new = $str;
	$str_new = str_replace($arr_link_js, $arr_localpath_js, $str_new);
	return $str_new;
}


/**
**函数名：saveIMGFiles
**存储img文件原来的地址、文件名和下载在本地的路径
**var $str: 文件文本
**返回值  存在则修改过路径的文本；
**/
function saveIMGFiles($str){
	global $host;
	global $others;
	global $version;
	global $version_template;
	$localpath = OTHERS.BROWSER_SEPARATOR;
	$arr_link_img = array(); //保存img 文件完整link
	$arr_filename_img = array(); //保存img 文件的名字
	$arr_localpath_img = array();//保存img 文件本地存储路径
	$count = 0;	

	preg_match_all("/<img\s+.*?src=[\"|']([^\"']*)[\"|'].*?>/",$str,$images, PREG_SET_ORDER);//images 里保存了从页面获取的所有img文件的路径
	//存储img文件原来的地址、文件名和下载在本地的路径

	foreach($images as $val){	
		if(strpos($val[1], "http:")!==0 && substr($val[1], 0,1)!=="/"){		
			continue;
		}

		$arr_link_img[$count] = $val[1];
		if(strpos($val[1], "http:")!==0 && substr($val[1], 0,1)=="/"){	
			array_push($arr_link_img, $val[1]);	
			//$val[1] = $images[$count][1] = "http://".$host.$val[1];
		}	
	
		$parts_img = parse_url($val[1]);
		if(!$parts_img['path']){
			continue;
		}
	
		$filname_img = basename($parts_img['path']);//获取pathname
		$arr_filename_img[$count] = $filname_img;
		//判断链接有效性
		if(get_headers($val[1])!==false){		
				$str_file_content = file_get_contents($val[1]);
    		$newfilepath = $version.DIRECTORY_SEPARATOR.$localpath.$filname_img;
    		$arr_localpath_img[$count] = $localpath.$filname_img;
    
    		//如果旧版本中不存在该文件，则直接下载该文件
    		$old_version = isFileExist($filname_img);
    	
    		$oldfilepath = "";
    		if($old_version === false){
    			file_put_contents($newfilepath, $str_file_content);
    			
    		}else{	
    			$oldfilepath = $version_template.$old_version.DIRECTORY_SEPARATOR.OTHERS.DIRECTORY_SEPARATOR.$filname_img;
    			$tempfilepath = TEMP.DIRECTORY_SEPARATOR.$filname_img;
    			file_put_contents($tempfilepath, $str_file_content);
    			if(!compare($oldfilepath, $tempfilepath)){
    				file_put_contents($newfilepath, $str_file_content);
    			}else{
    				$arr_localpath_img[$count] = "..".BROWSER_SEPARATOR.V.$old_version.BROWSER_SEPARATOR.OTHERS.BROWSER_SEPARATOR.$filname_img;
    				
    			}
    		}
		}
		
		$count++;
	}
	//把html文件里的img路径更改指向保存的路径
	$str_new = $str;
	$str_new = str_replace($arr_link_img, $arr_localpath_img, $str_new);
	return $str_new;
}

/**
**函数名：recursive_delete
**删除文件夹里所有的文件
**var $dir: 文件夹路径
**/
function recursive_delete($dir)
{
	if(is_dir($dir)){
	   if($dh = opendir($dir)){
		   while(($file = readdir($dh)) !== false ){
				if($file != "." && $file != "..")
				{
					if(is_dir($dir.$file))
					{                               
					  recursive_delete($dir.$file."/"); 
					  rmdir($dir.$file );
					}
					else
					{
					  unlink( $dir.$file);
					}
				}
		   }
		   closedir($dh);
	   }
	}
}

/**
**函数名： compare
**作用： 对比两个文件
**var file1:文件1的路径  file2: 文件2的路径
**参考文献：http://www.php.net/manual/zh/function.md5-file.php
**          
**/


function compare($file1, $file2){
	return files_identical($file1, $file2);
}

function files_identical($fn1, $fn2) {
    if(filetype($fn1) !== filetype($fn2))
        return FALSE;

    if(filesize($fn1) !== filesize($fn2))
        return FALSE;

    if(!$fp1 = fopen($fn1, 'rb'))
        return FALSE;

    if(!$fp2 = fopen($fn2, 'rb')) {
        fclose($fp1);
        return FALSE;
    }

    $same = TRUE;
    while (!feof($fp1) and !feof($fp2))
        if(fread($fp1, READ_LEN) !== fread($fp2, READ_LEN)) {
            $same = FALSE;
            break;
        }

    if(feof($fp1) !== feof($fp2))
        $same = FALSE;

    fclose($fp1);
    fclose($fp2);

    return $same;
}

//修改a标签的link， 如果是相对路径，则改为绝对路径。
function changeALink($str){
	global $parts;
	global $host;
	$absolute_path = $parts['scheme']."://".$host;	
	$str_new = $str;
	$pattern = "/(<a\s+.*?href=[\"|'])(\/[^\"\']*)([\"|'].*?>)/";//注意这里用到的“？”，此处要用非贪吃模式
	$replacement = '${1}'.$absolute_path.'$2$3';
	$str_new = preg_replace($pattern, $replacement, $str_new);
	return $str_new;
}

function addToDB(){
	global $v;
	global $verpagepath_local;
	global $folder_name;
	
	$ver_arr = array(V.$v=>$verpagepath_local);
//var_dump($ver_arr);
	addNewVersion($folder_name, $ver_arr);
}
?>