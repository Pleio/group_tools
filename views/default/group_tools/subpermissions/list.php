<?php
$subpermission_id = elgg_extract("subpermission_id", $vars);
$access_collection = get_access_collection($subpermission_id);
$group_guid = (int) get_input("group_guid");
?>

<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<?php 
			echo elgg_view('output/confirmlink', array(
				"text" => elgg_echo("delete"),
				"href" => "action/group_tools/subpermissions/delete?access_guid=" . $subpermission_id . "&group_guid=" . $group_guid,
				"class" => "elgg-button-action group-tools-button-right",
				"confirm" => elgg_echo("group_tools:subpermissions:delete:confirm")
			));

			echo elgg_view('output/url', array(
				"text" => elgg_echo("group_tools:subpermissions:add_member"),
				"href" => "groups/subpermissions_add_member/" . $group_guid . "?access_guid=" . $subpermission_id,
				"class" => "elgg-button-action group-tools-button-right group-tools-subpermissions-add-member"
			));
		?>
		<h3>
			<?php echo $access_collection->name; ?>
		</h3>
	</div>
	<div class="elgg-body" id="custom_fields_profile_types_list_custom">
		<?php
			echo elgg_view('group_tools/subpermissions/view', array(
				'group_guid' => $group_guid,
				'access_guid' => $subpermission_id,
				'members' => get_members_of_access_collection($subpermission_id)
			))
		?>
	</div>
</div>
