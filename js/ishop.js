$(function() {
	var current_url;
	$("body").on("click", "a", function(e){
		e.preventDefault();
		var url = $(this).attr("href");
		if(url != current_url){
			$.ajax({
				type: "POST",
				url: url,
				data: ($(this).is("[data-id]")) ? 'id=' + $(this).data("id") : '',
				success: function (ret) {
					if ($('#mainMenu a[href="' + url + '"]').length){
						$('#mainMenu a').removeClass('selected');
						$('#mainMenu a[href="' + url + '"]').addClass('selected');
					}
					$("#mainContent" ).html( ret );
					current_url = url;
				}
			});
		}
	});
});