<?php
$members = elgg_extract("members", $vars);
$group_guid = elgg_extract("group_guid", $vars);
$access_guid = elgg_extract("access_guid", $vars);

// make it accessible by lazy_hover
set_input('access_guid', $access_guid);

foreach ($members as $member) {
	echo elgg_view_entity($member);
}