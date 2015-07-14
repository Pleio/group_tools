<?php
/**
 * add/remove a user as a group admin
 */

$loggedin_user = elgg_get_logged_in_user_entity();

$group_guid = (int) get_input("group_guid");
$user_guid = (int) get_input("user_guid");

$group = get_entity($group_guid);
$user = get_user($user_guid);

if (!empty($group) && !empty($user)) {
	if (($group instanceof ElggGroup) && $group->canEdit() && $group->isMember($user) && ($group->getOwnerGUID() != $user->getGUID())) {

		if (!check_entity_relationship($user->getGUID(), "group_admin", $group->getGUID())) {
			if (add_entity_relationship($user->getGUID(), "group_admin", $group->getGUID())) {
				$subject = elgg_echo("group_tools:notify:newadmin:subject", array($group->name));
				$message = elgg_echo("group_tools:notify:newadmin:message", array(
					$user->name,
					$group->name,
					$group->getURL())
				);

				$admins = group_tools_get_admins($group);
				foreach ($admins as $admin) {
					notify_user($admin->guid, $group->guid, $subject, $message);
				}

				system_message(elgg_echo("group_tools:action:toggle_admin:success:add"));
			} else {
				register_error(elgg_echo("group_tools:action:toggle_admin:error:add"));
			}
		} else {
			if (remove_entity_relationship($user->getGUID(), "group_admin", $group->getGUID())) {
				system_message(elgg_echo("group_tools:action:toggle_admin:success:remove"));
			} else {
				register_error(elgg_echo("group_tools:action:toggle_admin:error:remove"));
			}
		}
	} else {
		register_error(elgg_echo("group_tools:action:toggle_admin:error:group"));
	}
} else {
	register_error(elgg_echo("group_tools:action:error:input"));
}

forward(REFERER);
