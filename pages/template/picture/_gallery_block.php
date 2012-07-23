<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
?>
<div style="display: block; float: left;  text-align: center; padding: 20px; margin: 10px; ">
	<div style="height: 250px; margin-bottom: 5px;">
		<a href="{link}" rel="lightbox"><img src="{thumbnail}" class="img-dec" /></a><br/><br/>
	</div>
	
	<a href="{downloadlink}">Download</a>
	{if}{can_action}!=""{/if}
		| <a href="{CURRENT_PAGE}/delete/{pictureID}">Löschen</a>
	{/endif}
</div>