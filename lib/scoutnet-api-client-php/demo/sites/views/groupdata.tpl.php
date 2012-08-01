<?function _sn_navigator_groupdata_urls( $urls ){?>	
	<?foreach($urls as $url):?>
		<li>
			<a href="<?=$url->url?>"><i class="icon-bookmark"></i> <?=$url->url?></a>
			<?if($url->text):?>(<?=$url->text?>)<?endif?>
		</li>
	<?endforeach?>
<?}?>

			</li>
	<a href="mailto:kalender@scoutnet.de?subject=<?=htmlentities(rawurlencode(
		'Ergänzung Daten '.$group->name.' (ID '.$group->global_id.')'
	),ENT_COMPAT,'UTF-8')?>&body=<?=htmlentities(rawurlencode(
		'Liebes ScoutNet-Team,

ich möchte folgende Daten zu '.$group->name.' (ID '.$group->global_id.') ergänzen/korrigieren:

Ort:       
Stadtteil: 
PLZ:       
Homepage:  
Stammesnummer: 

Gut Pfad,


	'),ENT_COMPAT,'UTF-8')?>">Daten ergänzen/korrigieren</a> (oder Mail an kalender@scoutnet.de)

<dl class="dl-horizontal groupdata">
	<dt>Adresse</dt>
	<dd>
	<?if($group->zip ||$group->city):?>
		<?=$group->zip?> <?=$group->city?> <?if($group->district):?>(Stadtteil <?=$group->district?>)<?endif?>
	<?else:?>
		<i>- unbekannt -</i>
	<?endif?>
	</dd>

<?try{ob_start();?>
	<dt>Verband</dt>
	<dd>
	<?=$group->parent('national_association')->name?>
	</dd>
<?ob_end_flush();}catch(SN_Exception_EndUser $e){ob_end_clean();}?>

	<?if($group->internal_id):?>
		<dt>Stammesnummer</dt>
		<dd><?=$group->internal_id?></dd>
	<?endif?>

	<dt>Ebene</dt>
	<dd><?=$group->layer?></dd>

	<?if(count($urls = $group->urls)):?>
		<dt>URLs</dt>
		<dd>
			<ul>
				<?_sn_navigator_groupdata_urls( array_slice($urls->getArrayCopy(),0,5) )?>
			</ul>
			<?if(count($urls) > 5):?>
				<button class="btn" data-toggle="collapse" data-target="#more-urls">mehr URLs...</button>
				<ul id="more-urls" class="collapse in">
					<li>&nbsp;</li>
					<?_sn_navigator_groupdata_urls( array_slice($urls->getArrayCopy(),5) )?>
				</ul>
			<?endif?>
		</dd>
	<?endif?>
	
	<dt>ScoutNet ID</dt>
	<dd><?=$group->global_id?></dd>

	<dt>ScoutNet-Links</dt>
	<dd>
		<ul>
			<li><a href="https://www.scoutnet.de/community/permissions/request/?id=<?=$group->global_id?>"><i class="icon-fire"></i> Rechte beantragen</a>, oder mit Rechten:</li>
			<li><a href="https://www.scoutnet.de/community/pfadiindex/?ID=<?=$group->global_id?>&task=overview"><i class="icon-fire"></i> Daten bearbeiten</a></li>
			<li><a href="https://www.scoutnet.de/community/kalender/events.html?SSID=<?=$group->global_id?>&task=overview"><i class="icon-fire"></i> Termine bearbeiten</a></li>
			<li><a href="https://www.scoutnet.de/community/permissions/admin/?id=<?=$group->global_id?>"><i class="icon-fire"></i> Rechte verwalten</a></li>
		</ul>
	</dd>
</dl>
