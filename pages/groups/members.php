<?php

$guid = get_input('group_guid');

$dbprefix = elgg_get_config('dbprefix');

elgg_set_page_owner_guid($guid);

$group = get_entity($guid);
if (!$group || !elgg_instanceof($group, 'group')) {
    forward();
}

group_gatekeeper();

$title = elgg_echo('groups:members:title', array($group->name));

elgg_push_breadcrumb($group->name, $group->getURL());
elgg_push_breadcrumb(elgg_echo('groups:members'));

$content = elgg_list_entities_from_relationship(array(
    'relationship' => 'member',
    'relationship_guid' => $group->guid,
    'inverse_relationship' => true,
    'type' => 'user',
    'limit' => 20,
    'joins' => array('INNER JOIN ' . $dbprefix . 'users_entity o ON (e.guid = o.guid)'),
    'order_by' => 'o.name'
));

if (elgg_is_active_plugin('search')) {
    $sidebar = elgg_view("groups/sidebar/search", array("entity" => $group, "entity_type" => "user"));
}

$params = array(
    'content' => $content,
    'title' => $title,
    'sidebar' => $sidebar,
    'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);

?>