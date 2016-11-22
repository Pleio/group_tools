<?php
/**
 * content of the featured groups widget
 */

$widget = elgg_extract("entity", $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 5;
}

$show_random = $widget->show_random;

$featured_options = array(
	"type" => "group",
	"limit" => $num_display,
	"full_view" => false,
	"pagination" => false,
	"metadata_name_value_pairs" => array(
		"featured_group" => "yes"
	)
);

$list = elgg_list_entities_from_metadata($featured_options);

if (empty($list)) {
	$list = elgg_echo("notfound");
}

echo $list;
