<?php
/**
 * content of the index groups widget
 */

$widget = $vars["entity"];

// get widget settings
$count = sanitise_int($widget->group_count, false);
if (empty($count)) {
	$count = 8;
}

$options = array(
	"type" => "group",
	"limit" => $count,
	"full_view" => false,
	"pagination" => false,
	"metadata_name_value_pairs" => []
);

// limit to featured groups?
if ($widget->featured == "yes") {
	$options["metadata_name_value_pairs"][] = array(
		"name" => "featured_group",
		"value" => "yes"
	);
}

$getter = "elgg_get_entities_from_metadata";

// list groups
$groups = elgg_list_entities($options, $getter);
if (!empty($groups)) {
	echo $groups;
} else {
	echo elgg_echo("groups:none");
}