$(function() {
	function web2pic(url)
	{
		$("#showcase_button").attr("disabled","true");
		var urlenc = encodeURIComponent(url);
		var imageURL = "api.php?js=no&cache=1&viewport=1200x330&url="+urlenc;
		//$("#intro-header").css('background-image', 'url(\'/img/loading.gif\')');
		
		$("#loading").show();

		console.log(imageURL);
		
		$("#preloader").attr('src',imageURL);
		$("#preloader").load(function() {
			$("#intro-header").css('background-image', 'url(\'' + imageURL + '\')');
			$("#loading").hide();
			$("#showcase_button").removeAttr("disabled");
		});
	}

	$("#showcase_button").click(function() {
		web2pic($('#showcase_url').val());
	});

	$("#showcase_url").keypress(function(e) {
		if(e.keyCode === 13) {
			web2pic($("#showcase_url").val());
		}
	});
});