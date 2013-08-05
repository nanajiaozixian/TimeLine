window.onload = function() {
	  document.getElementById("show").onclick = function(){
		  var url = document.getElementById("addr").value;
		  $.ajax({
		  	type:"POST",
		  	url:"dealDatas.php",
		  	data:{pageurl: url}
		  }).done(function(msg){
		  	document.getElementById("webpage").src = msg;
		 		 document.getElementById("webpage").style.display = "block";
		  });
		  
		  return;
	  }
 }