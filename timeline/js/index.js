window.onload = function() {
	
		//timeline功能
	  document.getElementById("show").onclick = function(){
		  var url = document.getElementById("addr").value;
		  $.ajax({
		  	type:"POST",
		  	url:"dealDatas.php",
		  	data:{pageurl: url},
		  	//dataType: "json",//希望回调函数返回的数据类型
		  	success:function(json){
		  			getProfile(json);
		  		}
		  });
	  } 
	  
	  //snapshot功能
	   document.getElementById("snap").onclick = function(){
	   	var url = document.getElementById("addr").value;
		  var ver = document.getElementById("vers").value;
		  $.ajax({
		  	type:"POST",
		  	url:"savefiles.php",
		  	data:{version: ver, pageurl: url},
		  	//dataType: "json",//希望回调函数返回的数据类型
		  	success:function(msg){
		  			console.log(msg);
		  		}
		  });
	  } 
	  
 }
 
function getProfile(json){
			var paths = eval("("+json+")");
			if(paths.length ==0){
				alert("The page has not any local vertion now. Go to make some snapshots now!");
				return;
			}
			drawTimeline(paths);
		
}

function drawTimeline(filesPath){
		
			$("#dates").empty();
				for(var i=0; i<filesPath.length;i++){
				$.each(filesPath[i], function(key, value){
					value = value.replace(/\\/g, "/");
				$("#dates").append(
					'<li><a href="'+value+'">'+key+'</a></li>');
				})
				}
			
			//timline插件
			$(function(){
			$().timelinr({
				orientation: 	'vertical',
				issuesSpeed: 	300,
				datesSpeed: 	100,
				arrowKeys: 		'true',
				startAt:		1
			})
		});
	
	
}