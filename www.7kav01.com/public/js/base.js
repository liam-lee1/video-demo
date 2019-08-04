$(function(){
	$("img").onload=function(){
		$(this).attr("src", "../images/default.jpg");
	}

	$("img").one("error", function(e){
		$(this).attr("src", "../images/default.jpg");
	});
})