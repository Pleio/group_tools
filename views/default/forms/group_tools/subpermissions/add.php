<?php

$guid = (int) get_input("group_guid");

$form_data = "<div>";
$form_data .= "<label>" . elgg_echo("group_tools:subpermissions:name") . "</label>";
$form_data .= elgg_view("input/text", array("name" => "name"));
$form_data .= elgg_view("input/hidden", array("name" => "group_guid", "value" => $guid));
$form_data .= "</div>";

$form_data .= elgg_view("input/submit", array("name" => "submit", "value" => elgg_echo("save")));

echo $form_data;