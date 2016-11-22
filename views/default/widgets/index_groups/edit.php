<?php
/**
 * settings for the index group widget
 */

$widget = $vars["entity"];

$count = sanitise_int($widget->group_count, false);
if (empty($count)) {
	$count = 8;
}

$noyes_options = array(
	"no" => elgg_echo("option:no"),
	"yes" => elgg_echo("option:yes")
);

?>
<div>
	<?php echo elgg_echo("widget:numbertodisplay"); ?><br />
	<?php echo elgg_view("input/text", array("name" => "params[group_count]", "value" => $count, "size" => "4", "maxlength" => "4")); ?>
</div>

<div>
	<?php
		echo elgg_echo("widgets:index_groups:featured");
		echo "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[featured]", "options_values" => $noyes_options, "value" => $widget->featured));
	?>
</div>