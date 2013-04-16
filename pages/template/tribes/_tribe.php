<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
?>
<h1>{name}</h1>
<dl>
	<dt>Adresse</dt>
	<dd>{zip} {city}{if} {district} != "" {/if}, {district} {/endif}</dd>
	
	<dt>Homepage</dt>
	<dd><a href="{url}" target="_blank">{url}</a></dd>
	
	<dt>Termine</dt>
	<dd><a href="{LINK_MAIN}calender/{id}">Kalender von {name}</a></dd>
</dl>

{map}