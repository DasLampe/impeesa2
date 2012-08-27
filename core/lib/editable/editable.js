$(document).ready(function() {
	/*@FIXME: Not display when empty. -.-
	 * 
	 * $('#content[contenteditable="true"]').focusin(function() {
		var placeholder="Hier Text eingeben";
		if($(this).text().trim().toLowerCase() == placeholder.trim().toLowerCase())
		{
			$(this).empty();
		}
	})*/;
	
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

		request.addEventListener('load', function(evt) {
			evt12 = JSON.parse(request.responseText);
			$('#content[contenteditable="true"]').prepend('<img src="'+evt12.picture_link+'" class="img-dec float-right" />');
		}, false);

		// FormData Objekt erstellen
		var data = new FormData();
		data.append('file', file);

		// Datei hochladen
		request.send(data);
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
		
		$.ajax({
			url: window.location,
			type: "POST",
			typeData: "json",
			data: data+"&submit=True",
			success: function(data) {
				alert(data.msg);
				$('#save').remove();
			}
		});
	});
});