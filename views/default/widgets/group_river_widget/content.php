<?php
/**
 * content for the group river/activity widget
 */

$widget = $vars["entity"];

// determine widget group
if ($widget->context == "groups") {
	$group_guid = array($widget->getOwnerGUID());
} else {
	$group_guid = $widget->group_guid;
	if (!empty($group_guid)) {
		if (!is_array($group_guid)) {
			$group_guid = array($group_guid);
		}
	}
}

if (!empty($group_guid)) {
	$group_guid = array_map("sanitise_int", $group_guid);
	$key = array_search(0, $group_guid);
	if ($key !== false) {
		unset($group_guid[$key]);
	}
}

if (!empty($group_guid)) {
	// get activity filter
	$activity_filter = $widget->activity_filter;

	//get the number of items to display
	$dbprefix = elgg_get_config("dbprefix");
	$offset = 0;
	$limit = (int) $widget->num_display;

	if ($limit < 1 | $limit > 50) {
		$limit = 10;
	}

	$wheres = array();

	if ($activity_filter) {
		list($type, $subtype) = explode(',', $activity_filter);

		if (!empty($type)) {
			$wheres[] = "rv.type = '" . sanitise_string($type) . "'";
		}

		if (!empty($subtype)) {
			$wheres[] = "rv.subtype = '" . sanitise_string($subtype) . "'";
		}
	}

	$options = array(
		'limit' => $limit,
		'offset' => $offset,
		'joins' => array("JOIN {$dbprefix}entities e1 ON e1.guid = rv.object_guid"),
	);

	// run two seperate queries for optimization reasons
	$options['wheres'] = array_merge($wheres, array("e1.container_guid IN (" . implode(',', $group_guid) . ")"));
	$items = elgg_get_river($options);

	$options['wheres'] = array_merge($wheres, array("rv.object_guid IN (" . implode(',', $group_guid) . ")"));
	$more_items = elgg_get_river($options);

	$items = array_merge($items, $more_items);

	// sort the items again based on posted DESC
	usort($items, function($i,$j) {
		if ($i->posted == $j->posted) {
			return 0;
		}

		return ($i->posted > $j->posted) ? -1 : 1;
	});

	if (!empty($items)) {
		// return only the first $limit items
		$items = array_slice($items, 0, $limit);

		$options = array(
			"pagination" => false,
			"count" => count($items),
			"items" => $items,
			"list_class" => "elgg-list-river elgg-river",
			"limit" => $limit,
			"offset" => $offset
		);

		$river_items = elgg_view("page/components/list", $options);
	} else {
		$river_items = elgg_echo("widgets:group_river_widget:view:noactivity");
	}

	// display
	echo $river_items;
} else {
	echo elgg_echo("widgets:group_river_widget:view:not_configured");
}

