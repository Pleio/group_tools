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
if (!$group | !elgg_instanceof($group, "group")) {
	register_error(elgg_echo("groups:noaccess"));
	forward(REFERER);
}

if (!$group->canEdit()) {
	register_error(elgg_echo("groups:noaccess"));
	forward(REFERER);
}

if ($group->subpermissions_enable != "yes") {
	register_error(elgg_echo("group_tools:subpermissions:notenabled"));
	forward(REFERER);
}

$subpermissions = unserialize($group->subpermissions);
if (!is_array($subpermissions)) {
	register_error(elgg_echo("group_tools:subpermissions:notenabled"));
	forward(REFERER);
}

if (!in_array($access_id, $subpermissions)) {
	register_error(elgg_echo("groups:noaccess"));
	forward(REFERER);
}

$access_collection = get_access_collection($access_id);
if (!$access_collection) {
	register_error(elgg_echo("groups:noaccess"));
	forward(REFERER);
}

$title = elgg_echo("group_tools:subpermissions:manage_members") . ": " . $access_collection->name;

// change page title
elgg_push_breadcrumb(elgg_echo("groups"), "groups/all");
elgg_push_breadcrumb($group->name, $group->getURL());
elgg_push_breadcrumb($title);

$content = elgg_view_form("group_tools/subpermissions/manage_members", null, array(
	'entities' => $group->getMembers(0, 0, false),
	'value' => get_members_of_access_collection($access_id, true)
));

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
