<?php
$group = elgg_extract("entity", $vars);
$prefix = "group_tools:restrict_discussions:";

$noyes_options = array(
    "no" => elgg_echo("option:no"),
    "yes" => elgg_echo("option:yes")
);

$form_body = "<div class='elgg-quiet'>" . elgg_echo("group_tools:restrict_discussions:description") . "</div>";

$form_body .= "<div>";
$form_body .= elgg_echo("group_tools:restrict_discussions:title");
$form_body .= ":&nbsp;" . elgg_view("input/dropdown", array("name" => "enabled", "options_values" => $noyes_options, "value" => $group->getPrivateSetting($prefix . "enabled")));
$form_body .= "</div>";

$form_body .= "<div>";
$form_body .= elgg_view("input/hidden", array("name" => "group_guid", "value" => $group->getGUID()));
$form_body .= elgg_view("input/submit", array("value" => elgg_echo("save")));
$form_body .= "</div>";

$title = elgg_echo("group_tools:restrict_discussions:title");
$body = elgg_view("input/form", array(
    "action" => $vars["url"] . "action/group_tools/restrict_discussions",
    "body" => $form_body
));

echo elgg_view_module("info", $title, $body);
