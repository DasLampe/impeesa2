$(document).ready(function() {
	/*@FIXME: Not display when empty. -.-
	 * 
	 * $('#content[contenteditable="true"]').focusin(function() {
		var placeholder="Hier Text eingeben";
		if($(this).text().trim().toLowerCase() == placeholder.trim().toLowerCase())
		{
			$(this).empty();
		}
	});*/
	
	$('#content[contenteditable="true"]').focusout(function() {
		if(!$(this).text().length)
		{
			$(this).html('Hier Text eingeben');
		}
	});
	

	$('#headline[contenteditable="true"]').focusout(function() {
		if(!$(this).text().length)
		{
			$(this).html('Überschrift');
		}
	});
	
	$('#add-file').click(function() {
		$(this).next('input').trigger('click');
	});
	
	$('input[type="file"]').change(function() {
		console.log("Upload image");
		var request = new XMLHttpRequest(),
		file = this.files[0];
		// XMLHttpRequest öffnen
		request.open('POST', window.location, true);
		request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

		request.addEventListener('load', function(evt) {
			response = JSON.parse(request.responseText);
			if(response.status == "success")
			{
				$('#content[contenteditable="true"]').prepend('<img src="'+response.picture_link+'" class="img-dec float-right" />');
			}
			show_msg(response.msg, response.status);
		}, false);

		// FormData Objekt erstellen
		var data = new FormData();
		data.append('file', file);

		// Datei hochladen
		request.send(data);
		request.onreadystatechange = function() {
			if(request.readyState == '2')
			{
				response	= JSON.parse(request.responseText);
				show_msg("Bild wird hochgeladen. Einen Augenblick", "info");
			}
		};
		$(this).val('');
	});
	
	$('#save').click(function() {
		data	= new Object();
		
		var allEditable	= $('*[contenteditable="true"]');
		for(var i = 0; i<allEditable.length; i++)
		{
			data[$(allEditable[i]).attr('id')]	= $(allEditable[i]).html();
		}
		
		data	= $.param(data);
		var url = window.location.href;
		var link_main	= "{LINK_MAIN}";
		url	= url.substr(link_main.lastIndexOf("/"));
		if(url.search("/admin/") >= 0)
		{
			url	= url.slice(6);
		}
		
		$.ajax({
			url: "{LINK_MAIN}admin"+url,
			type: "POST",
			typeData: "json",
			data: data+"&submit=True",
			success: function(data) {
				show_msg(data.msg, data.status);
			}
		});
	});
});