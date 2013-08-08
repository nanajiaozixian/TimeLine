<?php

//$pagehostname = "cn.com.adobe.www";

function newMongoClient(){
	global $mongo;
	$mongo = new MongoClient();
}


function addNewVersion($collection_name,$vers_arr){
	global $mongo;
	if($mongo==null){
		$mongo = new MongoClient();
	}
	try{
    	$war_db = $mongo->selectDB("pages");
   }catch(Exception $e){
    	$war_db = new MongoDB($mongo, "pages");
   }
        
  try{
  	$pagehost_collection = $war_db->selectCollection($collection_name);
  }catch(Exception $e){
  	$pagehost_collection = new createCollection($collection_name);
  }
	$rs = $pagehost_collection->insert($vers_arr);	
}

function getMyPageCollect($pagehostname){
	global $mongo;
	if($mongo==null){
		$mongo = new MongoClient();
	}
	try{
    	$war_db = $mongo->selectDB("pages");
   }catch(Exception $e){
    	$war_db = new MongoDB($mongo, "pages");
   }
        
  try{
  	$pagehost_collection = $war_db->selectCollection($pagehostname);
  }catch(Exception $e){
  	$pagehost_collection = new createCollection($pagehostname);
  }
  
  //$pagehost_collection->remove();//Çå¿Õcollection
  $index = $pagehost_collection->find();
  $infor = array();
 
 while($index->hasNext()){
  	$ii = $index->getNext();
  	while(list($key, $val)=each($ii)){
  	
  		$l = array($key=>$val);
  		if($key=="_id"){
  			continue;
  		}
  		
  		//var_dump($l);
  		array_push($infor, $l);
		}
	}
	
	return $infor;
}
?>