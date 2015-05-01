<?php
/**
 * Manage group subpermissions.
 *
 * @package ElggGroups
 */

gatekeeper();

$guid = (int) get_input("group_guid");
$access_id = (int) get_input("access_guid");

elgg_set_page_owner_guid($guid);

$group = get_entity($guid);

if ($group->subpermissions_enable != "yes") {
	register_error(elgg_echo("group_tools:subpermissions:notenabled"));
	forward(REFERER);
}

$title = elgg_echo("group_tools:subpermissions:manage_members");

if (!empty($group) && elgg_instanceof($group, "group") && $group->canEdit() && in_array($access_id, unserialize($group->subpermissions))) {
	
	// change page title	
	elgg_push_breadcrumb(elgg_echo("groups"), "groups/all");
	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb($title);

	$content = elgg_view_form("group_tools/subpermissions/manage_members", null, array(
		'entities' => $group->getMembers(0, 0, false),
		'value' => get_members_of_access_collection($access_id, true)
	));

} else {
	$content = elgg_echo("groups:noaccess");
}

if(elgg_is_xhr()){
	echo "<div style='width:750px;height:400px;'>";
	echo elgg_view_title($title);
	echo $content;
	echo "</div>";
} else {
	$params = array(
		"content" => $content,
		"title" => $title,
		"filter" => "",
	);
	$body = elgg_view_layout("content", $params);	
	echo elgg_view_page($title, $body);
}
