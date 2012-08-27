$(document).ready(function () {
	
	/**
	 * Email Protection
	 */
	$('a').each(function() {
		var href, mailaddress;
		href = $(this).attr('href');
		if(href && href.search(/mailto:/) != -1)
		{
			mailaddress = href.substr(6);
			mailaddress	= Base64.decode(mailaddress);
			$(this).attr('href', 'mailto:'+mailaddress);
			
			if($(this).html().search(/ät|at/) != -1)
			{
				$(this).html(mailaddress);
			}
		}
	});
});

/*
 * Message handling
 * Show Info messages, like error or success
 */
function show_msg(msg, status) {
	$('body').append('<dic id="info-msg-wrapper"><div id="info-msg"></div></div>');
	$('#info-msg').addClass(status).html(msg);
	setTimeout("$('#info-msg-wrapper').fadeOut('slow')", 5000);
	setTimeout("$('#info-msg-wrapper').remove()", 6000); //Hack to remove container, but show nice fade out.
}