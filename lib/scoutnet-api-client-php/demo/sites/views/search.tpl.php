<h2>Ergebnisse:</h2>
<p>
<?if(count($groups)):?>
	<ul>
	<?foreach( $groups as $group ):?>
		<li style="font-size:150%;line-height: 140%;"><a href="?id=<?=$group->global_id?>"><?=$group->name?>
		<?if($group->layer == 'unit' && ($group->city || $group->district)):?>
			in
			<?=$group->city?><?if($group->city && $group->district):?>-<?endif?><?=$group->district?>
		<?endif?>
	<?endforeach?>
	</ul>
<?else:?>
	<i>- keine Gruppen gefunden -</i>
<?endif?>
</p>
