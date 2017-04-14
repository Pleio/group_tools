<?php
/**
 * Add subpermission group
 */

gatekeeper();

$group_guid = (int) get_input("group_guid");
$name = get_input("name");

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

if (!$name) {
	register_error(elgg_echo("group_tools:subpermissions:noname"));
	forward(REFERER);
}

$id = create_access_collection($name, $group->guid);

if ($group->subpermissions) {
	$subpermissions = unserialize($group->subpermissions);
}

if (!is_array($subpermissions)) {
    $subpermissions = array();
}

array_push($subpermissions, $id);

$group->subpermissions = serialize($subpermissions);
$group->save();

system_message(elgg_echo("group_tools:subpermissions:added"));
forward("/groups/subpermissions/" . $group->guid);