<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
?>
<div class="picture">
	<a href="{link}" rel="lightbox"><img src="{thumbnail}" class="img-dec" /></a>

	{if} {userCanDelete} == true {/if}
		<div class="options">
			<a href="{delete_link}" class="ym-delete">Löschen</a>
		</div>
	{/endif}
</div>