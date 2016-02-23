<?php
/**
 * Restrict discussions
 */

$group_guid = (int) get_input("group_guid");
$enabled = get_input("enabled");

$forward_url = REFERER;

$group = get_entity($group_guid);

if (!empty($group) && ($group instanceof ElggGroup)) {
	if ($group->canEdit()) {
		$prefix = "group_tools:restrict_discussions:";
		$group->setPrivateSetting($prefix . "enabled", $enabled);
		$forward_url = $group->getURL();
		system_message(elgg_echo("group_tools:actions:restrict_discussions:success"));
	} else {
		register_error(elgg_echo("groups:cantedit"));
	}
} else {
	register_error(elgg_echo("groups:notfound:details"));
}

forward($forward_url);
