$(document).ready(function () {
	$('a [class="ym-delete"]').click(function() {
		var parent	= $(this).parent();
		$.ajax({
			url: $(this).attr("href"),
			typeData: "JSON",
			success: function(data) {
				$(parent).remove();
				show_msg(data.msg, data.status);
			},
			error: function(data) {
				show_msg("Es ist ein Fehler aufgetreten!", "error");
			}
		});
		return false;
	});
});