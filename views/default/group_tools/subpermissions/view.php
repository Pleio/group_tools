<?php
$members = elgg_extract("members", $vars);

foreach ($members as $member) {
	echo elgg_view_entity($member);
}