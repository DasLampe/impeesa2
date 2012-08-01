<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>
		ScoutNet-Kalender <?=$group?>
		<?=($group->district OR $group->city) ? h($group->city):''?><?=($group->district AND $group->city) ? '-' : '' ?><?=$group->district?>
	</title>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
	<script type="text/javascript" src="behavior.js"></script>
	<script type="text/javascript" src="<?=SNK_URL?>js/base2-p.js"></script>
	<script type="text/javascript" src="<?=SNK_URL?>js/base2-dom-p.js"></script>
	<style type="text/css" media="none">.snk-termin-infos{display:none;}</style>
	<script type="text/javascript">
		base2.DOM.bind(document);
		snk_init();
		document.addEventListener('domcontentloaded', function(){ return snk_finish('<?=addslashes($_SERVER['request_uri'])?>'); }, false);
	</script>
</head>
<body>
<div id="snk-<?=$group->global_id?>" class="snk">
<div class="snk-body">
<div class="snk-datum">
	<?=strftime("Heute ist %A, der %d.%m.%Y.",time())?>
</div>
<div class="snk-uhrzeit">
	<?=strftime("Es ist %H:%M Uhr.",time())?>
</div>

<div class="snk-ebenene-menu">
	<form method="get" action="<?=$_SERVER['php_self']?>">
		<? foreach( $_GET as $var => $value ): ?>
			<? if($var != "upto"): ?>
				<input type="hidden" name="<?=$var?>" value="<?=$value?>" />
			<? endif ?>
		<? endforeach ?>
		<label for="snk-auswahlbox">Termine bis</label>
		<select id="snk-auswahlbox" name="upto">
		<? foreach( $group->parents(array("inclusive"=>true)) as $parent ): ?> 
			<option value="<?=($parent->layer != $group->layer)?$parent->layer:''?>"
				<? if((isset($_GET['upto']) && $_GET['upto'] == $parent->layer) || (!isset($_GET['upto']) && $group->layer == $parent->layer)): ?>
					selected
				<? endif ?> >
				<?=$parent->name?><?/*=$parent->layer?> <?/* if($parent->layer >= 6): ?> <?=$parent->name?><? endif */?>
			</option>
		<? endforeach ?>
    </select>
    	<span id="snk-anzeigen"></span><noscript><input type="submit" value="anzeigen" /></noscript>
    </form>
</div>

<div class="snk-termine">
<table>
	<caption>ScoutNet-Kalender <?=$group->national_association?> <?=$group->layer?> <?=$group->name?><? if($group->district OR $group->city): ?>, <?=$group->city?><? if( $group->district AND $group->city): ?>-<? endif ?><?=$group->district?><? endif ?></caption>
	<tr class="snk-headings-row"> 
		<? if(isset($_GET['upto'])):?><th class="snk-eintrag-ebene-ueberschrift">Ebene</th><? endif ?>
		<th class="snk-eintrag-datum-ueberschrift">Datum</th>
		<th class="snk-eintrag-zeit-ueberschrift">Zeit</th>
		<th class="snk-eintrag-titel-ueberschrift">Titel</th>
		<th class="snk-eintrag-stufen-ueberschrift">Stufen</th>
		<th class="snk-eintrag-kategorien-ueberschrift">Kategorien</th>
	</tr>
	<? foreach( $events->Group_By_Start_Date_Time('%Y-%m') as $monat => $events ): ?> 
	<tr> 
		<th colspan="6" class="snk-monat-heading"><?=strftime("%B '%y",strtotime($monat."-01"))?></th>
	</tr>
	<? foreach( $events as $eintrag ): ?> 
	<tr> 
		<? if(isset($_GET['upto'])): ?><td class="snk-eintrag-ebene"><?=strtr($eintrag->group()->layer,array(" "=>"&nbsp;"))?><? if( $eintrag->group()->layer >= 7): ?>&nbsp;<?=strtr($eintrag->group()->name,array(" "=>"&nbsp;"))?><? endif ?></td><? endif ?>
		<td class="snk-eintrag-datum">
			<?=substr(strftime("%A",strtotime($eintrag->start_date)),0,2)?><?=strftime(",&nbsp;%d.%m.",strtotime($eintrag->start_date))?>&nbsp;<? if($eintrag->end_date!= ""): ?>&nbsp;-&nbsp;<?=substr(strftime("%A",strtotime($eintrag->end_date)),0,2)?><?=strftime(",&nbsp;%d.%m.",strtotime($eintrag->end_date))?><? endif ?>
		</td>
		<td class="snk-eintrag-zeit"><?=strftime("%H:%M",strtotime($eintrag->start_time))?><? if(strtotime($eintrag->end_time)!= ""): ?>&nbsp;-&nbsp;<?=strftime("%H:%M",strtotime($eintrag->end_time))?><? endif ?></td>
		<td class="snk-eintrag-titel">
			<? if($eintrag->description || $eintrag->zip || $eintrag->location || $eintrag->organizer || $eintrag->target_group || $eintrag->url): ?>
			<a
				href="#snk-termin-<?=$eintrag->id?>" class="snk-termin-link"
				onclick="if(snk_show_termin) return snk_show_termin(<?=$eintrag->id?>,this); "
			>
				<?=$eintrag->title?>
			</a><? else: ?><?=$eintrag->title?><? endif ?>
		</td>
		<td class="snk-eintrag-stufe">
			<?/*=$group->sections()->fields("name")->implode(", ")*/?>
<?
$fields = $group->sections()->fields("name")->array_reverse(_)->sort();
print_r( $fields );
?>
			<?=$group->sections()->fields("name")->array_map("mb_strtolower",_)->implode("ast",_)?>
			<? /*foreach( $eintrag->stufe->records as $stufe ): ?>
				<img src="<?=SNK_URL?>2.0/images/<?=$stufe->id?>.gif" alt="<): ?=h($stufe->bezeichnung)?>" />
			<? endforeach*/ ?>			
		</td>
		<td class="snk-eintrag-kategorien"><?=implode(", ",$eintrag->keywords)?></td>
	</tr>
	<? if($eintrag->description || $eintrag->zip || $eintrag->location || $eintrag->organizer || $eintrag->target_group || $eintrag->url): ?>
	<tr id="snk-termin-<?=$eintrag->id?>" class="snk-termin-infos">
		<td colspan="6">
			<dl>
					<? if($eintrag->description): ?><dt class="snk-eintrag-beschreibung">Beschreibung</dt><dd><?=nl2br($eintrag->description)?></dd><? endif ?>
					<? if($eintrag->zip || $eintrag->location): ?><dt class="snk-eintrag-ort">Ort</dt><dd><?=$eintrag->zip?> <?=$eintrag->location?></dd><? endif ?>
					<? if($eintrag->organizer): ?><dt class="snk-eintrag-veranstalter">Veranstalter</dt><dd><?=$eintrag->organizer?></dd><? endif ?>
					<? if($eintrag->target_group): ?><dt class="snk-eintrag-zielgruppe">Zielgruppe</dt><dd><?=$eintrag->target_group?></dd><? endif ?>
					<? if($eintrag->url): ?><dt class="snk-eintrag-url">Link</dt><dd><a <? if( isset($_GET['link_target']) ): ?>target="<?=$_GET['link_target']?>" <? endif ?>href="<?=$eintrag->url?>"><? if($eintrag->url_text): ?><?=$eintrag->url_text?><? else: ?><?=$eintrag->url?><? endif ?></a></dd><? endif ?>
					<?/*<dt class="snk-eintrag-autor">Eingetragen von</dt><dd><? if($eintrag->autor->vorname || $eintrag->autor->nachname): ?><?=$eintrag->autor->vorname?>&nbsp;<?=$eintrag->autor->nachname?><? else: ?><?=$eintrag->autor->nickname?><? endif ?></dd>*/?>
			</dl>
		</td>
	</tr>
	<? endif ?>
	<? endforeach ?> 
	<? endforeach ?> 
</table>
</div>
</div>
<div class="snk-footer">
	<div class="snk-hinzufuegen">
		<a href="https://www.scoutnet.de/community/kalender/events.html?task=create&amp;SSID=<?=$group->global_id?>" target="_top">Termin&nbsp;hinzufügen</a>
	</div>
	<div class="snk-powered-by">
		Powered by <span><a href="http://kalender.scoutnet.de/" target="_top">ScoutNet.DE</a></span>
	</div>
</div>
</div>

</body>
</html>
