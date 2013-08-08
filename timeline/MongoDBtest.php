<?php

test();

function test(){
	global $mongo;
	if($mongo==null){
		$mongo = new MongoClient();
	}
	try{
    	$war_db = $mongo->selectDB("pages");
   }catch(Exception $e){
    	$war_db = new MongoDB($mongo, "pages");
   }
   //$war_db->drop();   //删除当前数据库
   //查看所有collections的内容
    
  $collections = $war_db->getCollectionNames();
  foreach($collections as $val){
  	
  	$cc = $war_db->selectCollection($val);
  	echo "$val <br/>";
  $index = $cc->find();
 
   while($index->hasNext()){
    	$ii = $index->getNext();
    	while(list($key, $val)=each($ii)){
    	
    		echo "$key => $val  <br/>";
  		}
  	}
  } 
 
}

?>