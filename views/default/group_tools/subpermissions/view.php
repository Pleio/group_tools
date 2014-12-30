<?php
$members = elgg_extract("members", $vars);
$group_guid = elgg_extract("group_guid", $vars);
$access_guid = elgg_extract("access_guid", $vars);

foreach ($members as $member) {
	$delete_member = array(
		'name' => 'delete_from_subgroup',
		'text' => elgg_echo("group_tools:subpermissions:delete_member"),
		'href' => "action/group_tools/subpermissions/delete_member?member_guid=" . $member->guid . "&group_guid=" . $group_guid . "&access_guid=" . $access_guid,
		'is_action' => true,
		'priority' => 0
	);

	elgg_register_menu_item("user_hover", ElggMenuItem::factory($delete_member));

	echo elgg_view_entity($member);
}

elgg_unregister_menu_item("user_hover", "delete_from_subgroup");