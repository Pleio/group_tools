<?php
$subpermission_id = elgg_extract("subpermission_id", $vars);
$access_collection = get_access_collection($subpermission_id);
?>

<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<?php echo elgg_view("output/url", array("text" => elgg_echo("add"), "href" => $vars["url"] . "profile_manager/forms/type", "class" => "elgg-button elgg-button-action group-tools-popup")); ?>
		<h3>
			<?php echo $access_collection->name; ?>
		</h3>
	</div>
	<div class="elgg-body" id="custom_fields_profile_types_list_custom">
		<?php
			echo elgg_view('group_tools/subpermissions/view', array(
				'members' => get_members_of_access_collection($subpermission_id)
			))
		?>
	</div>
</div>
