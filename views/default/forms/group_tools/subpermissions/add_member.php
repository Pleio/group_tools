<?php

$group_guid = (int) get_input("group_guid");
$access_guid = (int) get_input("access_guid");

$form_data = "<div>";
$form_data .= "<label>" . elgg_echo("group_tools:subpermissions:member") . "</label>";
$form_data .= elgg_view('input/autocomplete', array(
	'name' => 'username',
	'match_on' => 'users'

));
$form_data .= "</div>";

$form_data .= elgg_view("input/hidden", array("name" => "group_guid", "value" => $group_guid));
$form_data .= elgg_view("input/hidden", array("name" => "access_guid", "value" => $access_guid));
$form_data .= elgg_view("input/submit", array("name" => "submit", "value" => elgg_echo("save")));

echo $form_data;