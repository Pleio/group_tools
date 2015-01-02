<?php
global $CONFIG;

// only return results to logged in users.
if (!$user = elgg_get_logged_in_user_entity()) {
	exit;
}

if (!$q = get_input('term', get_input('q'))) {
	exit;
}

if (!$group_guid = (int) get_input('group_guid')) {
	exit;
}

$group = get_entity($group_guid);
if (!$group instanceof ElggGroup) {
	exit;
}

// replace mysql vars with escaped strings
$q = sanitise_string($q);
$q = str_replace(array('_', '%'), array('\_', '\%'), $q);

$limit = sanitise_int(get_input('limit', 10));
$site = elgg_get_site_entity();

$query = "SELECT * FROM
		{$CONFIG->dbprefix}users_entity as ue,
		{$CONFIG->dbprefix}entity_relationships as er,
		{$CONFIG->dbprefix}entities as e
	WHERE er.relationship = 'member'
		AND er.guid_one = ue.guid
		AND er.guid_two = {$group->getGUID()}		
		AND e.guid = ue.guid
		AND e.enabled = 'yes'
		AND ue.banned = 'no'
		AND (ue.name LIKE '$q%' OR ue.name LIKE '% $q%' OR ue.username LIKE '$q%')
	LIMIT $limit
";

if ($entities = get_data($query)) {
	foreach ($entities as $entity) {
		// @todo use elgg_get_entities (don't query in a loop!)
		$entity = get_entity($entity->guid);
		/* @var ElggUser $entity */
		if (!$entity) {
			continue;
		}

		$output = elgg_view_list_item($entity, array(
			'use_hover' => false,
			'class' => 'elgg-autocomplete-item',
		));

		$icon = elgg_view_entity_icon($entity, 'tiny', array(
			'use_hover' => false,
		));

		$result = array(
			'type' => 'user',
			'name' => $entity->name,
			'desc' => $entity->username,
			'guid' => $entity->guid,
			'label' => $output,
			'value' => $entity->username,
			'icon' => $icon,
			'url' => $entity->getURL(),
		);

		$results[$entity->name . rand(1, 100)] = $result;
	}
}

ksort($results);
header("Content-Type: application/json");
echo json_encode(array_values($results));
exit;
