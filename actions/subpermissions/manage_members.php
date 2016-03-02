<?php
/**
 * Manage subgroup members
 */

gatekeeper();

$group_guid = (int) get_input("group_guid");
$access_guid = (int) get_input("access_guid");
$member_guids = get_input("member_guids");

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
if (!is_array($subpermissions)) {
    register_error(elgg_echo("group_tools:subpermissions:nosubpermission"));
    forard(REFERER);
}

if (!in_array($access_guid, $subpermissions)) {
	register_error(elgg_echo("group_tools:subpermissions:nosubpermission"));
	forward(REFERER);
}

$group_member_guids = array();
foreach ($group->getMembers(0,0,false) as $member) {
	$group_member_guids[] = $member->guid;
}

// make sure only group members can be added
$member_guids = array_intersect($group_member_guids, $member_guids);

// add new members
$access_member_guids = get_members_of_access_collection($access_guid, true);
if ($access_member_guids == false) {
	$access_member_guids = array();
}

foreach (array_diff($member_guids, $access_member_guids) as $guid) {
	add_user_to_access_collection($guid, $access_guid);
}

// remove deselected members
foreach (array_diff($access_member_guids, $member_guids) as $guid) {
	remove_user_from_access_collection($guid, $access_guid);
}

forward('groups/subpermissions/' . $group->guid);