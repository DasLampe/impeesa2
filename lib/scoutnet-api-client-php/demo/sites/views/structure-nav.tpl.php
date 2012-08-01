<?function _sn_navigator_structurenav_groups( $heading, $groups ){
		global $group;
		$query = $_GET;
?>
		<?if(count($groups)):?>
			<li class="nav-header"><?=$heading?></li>
			<?foreach($groups as $_group):?>
				<?$query['id'] = $_group->global_id?>
				<?$query_string = htmlentities( http_build_query($query), ENT_COMPAT, 'UTF-8' )?>
				<li<?=$_group->global_id == $group->global_id?' class="active"':''?>>
					<a href="?<?=$query_string?>">
						<?=$_group->name?><?if($_group->layer == 'unit' && ($_group->city || $_group->district)):?> in
							<?=$_group->city?><?if($_group->city && $_group->district):?>-<?endif?><?=$_group->district?>
						<?endif?>
					</a>
				</li>
			<?endforeach?>
		<?endif?>
<?}?>
<div class="well sidebar-nav">
	<ul class="nav nav-list">
		<?_sn_navigator_structurenav_groups( 'Übergeordnete Gruppen', array_reverse($group->parents->getArrayCopy()) )?>
		<?try{$parent = $group->parent;}catch(Exception $e){$parent = NULL;}?>
		<?if($parent):?>
			<?_sn_navigator_structurenav_groups( 'Benachbarte Gruppen', $parent->children )?>
		<?endif?>
		<?_sn_navigator_structurenav_groups( 'Untergeordnete Gruppen', $group->children )?>
	</ul>
</div>
