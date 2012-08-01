<h2>Termine</h2>
<p>
<?
$years = range( date('Y')-3, date('Y')+4 );
$selected_year = !empty($_GET['year']) ? $_GET['year'] : date('Y');
$query = $_GET;
?>
<?if(!empty($_GET['year'])):?>
	<?$query['year'] = ''?>
	<a href="?<?=htmlentities( http_build_query($query), ENT_COMPAT, 'UTF-8' )?>">
		aktuelle
	</a>
<?else:?>
	<b>aktuelle</b>
<?endif?>
<?foreach( $years as $year ):?>
	|
	<?$query['year'] = $year?>
	<?if(empty($_GET['year']) || $year != $selected_year):?>
		<a href="?<?=htmlentities( http_build_query($query), ENT_COMPAT, 'UTF-8' )?>">
			<?=$year?>
		</a>
	<?else:?>
		<b><?=$year?></b>
	<?endif?>
<?endforeach?>
</p>
<?if(!empty($_GET['year'])):?>
	<?$events = $group->events('start_date >= ? AND start_date <= ?',array($selected_year.'-01-01',$selected_year.'-12-31'))?>
<?else:?>
	<?$events = $group->events('(start_date >= ? OR end_date >= ?) AND start_date <= ?',array($selected_year.date('-m-d'),$selected_year.date('-m-d'),$selected_year.'-12-31'))?>
<?endif?>
<?if(count($events)):?>
	<table class="table">
	<tr>
	<th>Datum</th><th style="width:100%">Titel</th>
	</tr>
	<?foreach( $events as $event ):?>
	<tr>
		<td style="white-space:nowrap;">
			<?=$event->start_date?>
			<?if(!$event->end_date || ($event->start_date === $event->end_date)):?>
				<br /> <?=$event->start_time?> <?if($event->end_time):?>- <?=$event->end_time?><?endif?>
			<?elseif(($event->start_date != $event->end_date)):?>
				<?if($event->end_time):?>
					<br /> - <?=$event->end_date?> <?=$event->end_time?>
				<?elseif($event->start_time):?>
					<?=$event->start_time?> <br /> - <?=$event->end_date?>
				<?else:?>
					- <?=$event->end_date?>
				<?endif?>
			<?endif?>
		<td>
			<p style="margin-bottom:0">
				<a style="cursor:pointer;" data-toggle="collapse" data-target="#event-details-<?=$event->id?>"><?=htmlentities($event->title,ENT_COMPAT,'UTF-8')?></a>
			</p>
			<div id="event-details-<?=$event->id?>" class="collapse in">
				<p style="margin-bottom:0;margin-top:9px;">
					<?=htmlentities($event->description,ENT_COMPAT,'UTF-8')?>
					<?/*<b>Stufen:</b> <?=$event->sections?>*/?>
				</p>
				<p>
					<?if($event->location):?>
						<br /><b>Veranstaltungsort:</b>
						<?=htmlentities($event->location,ENT_COMPAT,'UTF-8')?>
					<?endif?>
					<?if($event->zip):?>
						<?if(!$event->location):?><br /><?endif?><b>Postleitzahl:</b>
						<?=htmlentities($event->zip,ENT_COMPAT,'UTF-8')?>
					<?endif?>
					<?if($event->target_group):?>
						<br /><b>Zielgruppe:</b>
						<?=htmlentities($event->target_group,ENT_COMPAT,'UTF-8')?>
					<?endif?>
					<?if($event->organizer):?>
						<br /><b>Veranstalter:</b>
						<?=htmlentities($event->organizer,ENT_COMPAT,'UTF-8')?>
					<?endif?>
					<?if($event->url):?>
						<br /><b>Link:</b>
						<a href="<?=htmlentities($event->url,ENT_COMPAT,'UTF-8')?>"><?=htmlentities($event->url_text?$event->url_text:$event->url,ENT_COMPAT,'UTF-8')?></a>
					<?endif?>
					<br /><b>Tags:</b> <?=htmlentities($event->keywords->implode(", "),ENT_COMPAT,'UTF-8')?>
				</p>
			</div>
		</td>
	</tr>
	<?endforeach?>
	</table>
<?else:?>
<p>
	- keine Termine eingetragen -
</p>
<?endif?>