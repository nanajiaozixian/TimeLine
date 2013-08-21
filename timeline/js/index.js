

window.onload = function() {
			
		var pageurl="";
  	var copy_file_path = "";
		//timeline功能
	  document.getElementById("show").onclick = function(){
		  var url = document.getElementById("addr").value;
		  pageurl = url;
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
	   	pageurl = url;
		  var ver = document.getElementById("vers").value;
		  $.ajax({
		  	type:"POST",
		  	url:"preSavefiles.php",
		  	data:{version: ver, pageurl: url},
		  	success:function(msg){
		  			copy_file_path = msg;
		  			document.getElementById("hidepage").src = copy_file_path;
  					//document.getElementById("hidepage").style.display = "block";
  					addIFrameEvents();
		  		}
		  });
	  } 
	  	  

 
  function addIFrameEvents(){
  	var iframe = document.getElementById("hidepage");
  	if(iframe.attachEvent){
  		iframe.attachEvent("onload", function(){
  			//alert("Local iframe is now loaded.");
  			
  		});
  	}else{
  		iframe.onload = function(){
  			
  			var doc = document.getElementById('hidepage').contentDocument;
  			var links_arr = doc.getElementsByTagName("link");
  			
  			var hrefs = new Array();
  			for(var i=0; i<links_arr.length; i++){
  				if(links_arr[i].hasAttribute("href")){
						hrefs.push(links_arr[i].getAttribute("href"));
					}
  			}
  			
  			var scripts_arr = doc.getElementsByTagName("script");
  			var srcs = new Array();
  			for(var i=0; i<scripts_arr.length; i++){
  				if(scripts_arr[i].hasAttribute("src")){
						srcs.push(scripts_arr[i].getAttribute("src"));
					}
  			}
  		
  		
		 $.ajax({
		  	type:"POST",
		  	url:"download.php",
		  	data:{csshref: hrefs, jssrcs: srcs, page: pageurl, copyfile:copy_file_path},
		  	dataType: "json",//希望回调函数返回的数据类型
		  	success:function(json){
		  			
		  		}
		  });
  			
  			
  		}
  	}
  }
  
function getProfile(json){
			var paths = eval("("+json+")");
			if(paths.length ==0){
				alert("The page has not any local vertion now. Go to make some snapshots now!");
				return;
			}
			 var url = document.getElementById("addr").value;
			drawTimeline(paths);
		
}

function drawTimeline(filesPath){
			
		
			$("#dates").empty();
				for(var i=0; i<filesPath.length;i++){
				$.each(filesPath[i], function(key, value){
					value = value.replace(/\\/g, "/");
				$("#dates").append(
					'<li><a href="#'+key+'" path="'+value+'">'+key+'</a></li>');
					/*$("#issues").append(
					'<li id='+key+'>'+
					'<h1>'+key+'</h1>'+
					'<p>'+'hhhhhhhhhh'+'</p>'+
					'</li>');*/
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

}