function web2pic()
{
	$("#showcase_button").attr("disabled","true");
	var url = $("#showcase_url").val();
	var urlenc = encodeURIComponent(url);
	var imageURL = "api.php?js=no&token=0e316498db6211f940951e"+(urlenc)+"8be8cdf7089770&type=$type&viewport=1200x330&url="+urlenc;
	//$("#intro-header").css('background-image', 'url(\'/img/loading.gif\')');
	
	$("#loading").show();
	
	
	$("#preloader").attr('src',imageURL);
	$("#preloader").load(function() {
		$("#intro-header").css('background-image', 'url(\'' + imageURL + '\')');
		$("#loading").hide();
	});
	console.log(imageURL);

	setTimeout(function(){$("#showcase_button").removeAttr("disabled");},2000);
}