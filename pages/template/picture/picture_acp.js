$(document).ready(function () {
	$('.picture > .options > .ym-delete').click(function(event) {
		event.preventDefault();
		
		var picture_block	= $(this).parent().parent();
		$.ajax({
			url: $(this).attr("href"),
			typeData: "JSON",
			success: function(data) {
				$(picture_block).remove();
				show_msg(data.msg, data.status);
			},
			error: function(data) {
				show_msg("Es ist ein Fehler aufgetreten!", "error");
			}
		});
	});
});