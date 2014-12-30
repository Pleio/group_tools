<?php
/**
 * Delete member from subpermission group
 */

gatekeeper();

$group_guid = (int) get_input("group_guid");
$access_guid = (int) get_input("access_guid");
$member_guid = (int) get_input("member_guid");


if (empty($group_guid)) {
	register_error(elgg_echo("groups:cantedit"));
	forward(REFERER);
}

$group = get_entity($group_guid);
$member = get_entity($member_guid);

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

if (empty($member) | !$member instanceof ElggUser | !$group->isMember($member)) {
	register_error(elgg_echo("group_tools:subpermissions:nouser"));
	forward(REFERER);
}

remove_user_from_access_collection($member->guid, $access_guid);