<?php
/**
 * Manage group subpermissions.
 *
 * @package ElggGroups
 */

gatekeeper();

$guid = (int) get_input("group_guid");

elgg_set_page_owner_guid($guid);

$group = get_entity($guid);

if ($group->subpermissions_enable != "yes") {
	register_error(elgg_echo("group_tools:subpermissions:notenabled"));
	forward(REFERER);
}

$title = elgg_echo("group_tools:subpermissions");

elgg_push_context("subpermissions");

elgg_load_js("lightbox");
elgg_load_css("lightbox");

elgg_load_js('elgg.autocomplete');
elgg_load_js('jquery.ui.autocomplete.html');

if (!empty($group) && elgg_instanceof($group, "group") && $group->canEdit()) {
	
	// change page title	
	elgg_push_breadcrumb(elgg_echo("groups"), "groups/all");
	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb($title);

	elgg_register_menu_item('title', array(
		'name' => '',
		'href' => '/groups/subpermissions_add/' . $guid,
		'id' => 'group-tools-subpermissions-add',
		'text' => elgg_echo('group_tools:subpermissions:add'),
		'link_class' => 'elgg-button elgg-button-action',
	));


	$subpermissions = unserialize($group->subpermissions);

	if (!empty($subpermissions)) {
		foreach($subpermissions as $subpermission_id) {
			$content .= elgg_view('group_tools/subpermissions/list', array(
				'subpermission_id' => $subpermission_id
			));
		}
	} else {
		$content = elgg_echo("group_tools:subpermissions:nosubpermissions");
	}

} else {
	$content = elgg_echo("groups:noaccess");
}

$params = array(
	"content" => $content,
	"title" => $title,
	"filter" => "",
);
$body = elgg_view_layout("content", $params);

echo elgg_view_page($title, $body);

elgg_pop_context();