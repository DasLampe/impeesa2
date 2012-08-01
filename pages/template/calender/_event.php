<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
?>
<div class="box">
	<h2>{title}</h2>
	<div>
		<strong>Datum:</strong> {start_date} {if} {end_date} != ""{/if} - {end_date}{/endif}
	</div>
	<div>
		<strong>Gruppen:</strong> {groups}
	</div>
	<div>
		<strong>Art:</strong> {categorie}
	</div>
	
	{if} {description} != "" {/if}
	<h3>Info</h3>
	<p>
		{description}
	</p>
	{/endif}
</div>
