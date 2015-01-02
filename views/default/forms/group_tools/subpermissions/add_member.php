<?php

$group_guid = (int) get_input("group_guid");
$access_guid = (int) get_input("access_guid");

$form_data = "<div>";
$form_data .= "<label>" . elgg_echo("group_tools:subpermissions:member") . "</label>";
$form_data .= '<input type="text" value="" name="username" class="elgg-input-autocomplete ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">';
$form_data .= '<script type="text/javascript">';
$form_data .= "elgg.provide('elgg.autocomplete');";
$form_data .= "elgg.autocomplete.url = '" . elgg_get_site_url() . "groups/livesearch/" . get_input("group_guid") . "';";
$form_data .= "</script>";
$form_data .= "</div>";

$form_data .= elgg_view("input/hidden", array("name" => "group_guid", "value" => $group_guid));
$form_data .= elgg_view("input/hidden", array("name" => "access_guid", "value" => $access_guid));
$form_data .= elgg_view("input/submit", array("name" => "submit", "value" => elgg_echo("save")));

echo $form_data;