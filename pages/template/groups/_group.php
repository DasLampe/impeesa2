<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
?>
<div class="box">
	<h2>{group_name}</h2>
	<img src="<?= LINK_MAIN; ?>pages/template/groups/img/{group_logo}" class="float-right">
	<h3>Allgemeine Infos</h3>
	<p>
	<strong>Alter der Grüpplinge:</strong>
	{group_youngest} - {group_oldest}
	<br>
	<strong>Gruppenstunde:</strong>
	{group_day} von {group_begin} bis {group_end}</p>
	
	<h3>Weitere Infos zur Gruppe</h3>
	<p>
		{group_description}
	</p>
		
	<h3>Leiter</h3>
	<p>
		{group_leader}
	</p>
</div>