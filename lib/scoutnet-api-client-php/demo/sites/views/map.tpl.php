<?if($group->zip || $group->city):?>
<iframe height="350" style="width: 100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.de/maps?<?=htmlentities(
	http_build_query(array(
		//'f' => 'q',
		//'source' => 's_q',
		'hl' => 'de',
		//'geocode' => '',
		'q' => $group->zip.' '.$group->city.', '.$group->district,
		//'aq' => '',
		//'sll' => '51.151786,10.415039',
		//'sspn' => '11.421982,33.815918',
		//'t' => 'm',
		//'ie' => 'UTF8',
		//'hq' => '',
		//'hnear' => 'Dieselstraße, Halle (Saale)',
		//'ll' => '51.458285,12.01561',
		//'spn' => '0.149743,0.292511',
		'z' => '11',
		'output' => 'embed',
		'iwloc' => 'near'
	))
,ENT_COMPAT,'UTF-8')?>"></iframe>
<br />
Basierend auf Postleitzahl, Ort und Stadtteil.
<br />
<small>
	<a href="http://maps.google.de/maps?f=q&amp;source=embed&amp;hl=de&amp;q=<?=htmlentities(urlencode($group->zip.' '.$group->city.', '.$group->district),ENT_COMPAT,'UTF-8')?>" style="color:#0000FF;text-align:left">Größere Kartenansicht</a>
</small>
<?endif?>
