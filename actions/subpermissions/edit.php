<?php
global $CONFIG;

/**
 * Edit subpermission group
 */

gatekeeper();

$group_guid = (int) get_input("group_guid");
$access_guid = (int) get_input("access_guid");
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

$subpermissions = unserialize($group->subpermissions);

if (!in_array($access_guid, $subpermissions)) {
    register_error(elgg_echo("group_tools:subpermissions:nosubpermission"));
    forward(REFERER);
}

$access_guid = sanitise_int($access_guid);
$name = sanitize_string($name);

$result = update_data("UPDATE {$CONFIG->dbprefix}access_collections SET name = '{$name}' WHERE id = {$access_guid}");

if ($result) {
    system_message(elgg_echo("group_tools:subpermissions:add:created"));
} else {
    register_error(elgg_echo("group_tools:subpermissions:delete:cantdelete"));
}