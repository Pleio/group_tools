<?php
/**
 * Delete subpermission group
 */

gatekeeper();

$group_guid = (int) get_input("group_guid");
$access_guid = (int) get_input("access_guid");

if (empty($group_guid)) {
	register_error(elgg_echo("groups:cantedit"));
	forward(REFERER);
}

$group = get_entity($group_guid);
if (empty($group) | !$group instanceof ElggGroup | !$group->canEdit()) {
	register_error(elgg_echo("groups:cantedit"));
	forward(REFERER);
}

if ($group->subpermissions_enable != "yes") {
	register_error(elgg_echo("group_tools:subpermissions:notenabled"));
	forward(REFERER);
}

$subpermissions = unserialize($group->subpermissions);

if (!in_array($access_guid, $subpermissions)) {
	register_error(elgg_echo("group_tools:subpermissions:nosubpermission"));
	forward(REFERER);
}

if (delete_access_collection($access_guid)) {
	$subpermissions = array_diff($subpermissions, [$access_guid]);
	$group->subpermissions = serialize($subpermissions);
	$group->save();
} else {
	register_error(elgg_echo("group_tools:subpermissions:delete:cantdelete"));
}