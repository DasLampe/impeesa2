$(document).ready(function () {
        $( 'ul[id^="sortable-"]' ).sortable({
            connectWith: "ul",
			placeholder: "placeholder",
			forcePlaceholderSize: true
        });
 
        $( 'ul[id^="sortable-"]').disableSelection();
		
		$('#save').click(function() {
			var order="";
			$('ul[id^="sortable-"]').each(function () {
				var menu = $(this).parent().attr("id").slice($(this).parent().attr("id").lastIndexOf("-")+1);
				if(order != "")
				{
					order = order + "&";
				}	
				order = order + $(this).sortable( "serialize", { key : "menu["+menu+"][]"} );
			});
			
			$.ajax({
				url: window.location,
				data: "submit=submit&"+order,
				dataType: "json",
				type: "post",
				success: function(data) {
					show_msg(data.msg, data.status);
				},
				error: function() {
					show_msg("Es ist ein Fehler aufgetreten!", "error");
				}
			});
			return false;
		});
});