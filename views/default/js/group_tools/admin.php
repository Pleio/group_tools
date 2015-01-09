<?php
/**
 * extend the global JS on the admin side
 */
?>
//<script>
elgg.provide("elgg.group_tools_admin");

elgg.group_tools_admin.init = function() {

	$("#group-tools-special-states-tabs a").live("click", function() {
		// remove all selected tabs
		$("#group-tools-special-states-tabs li").removeClass("elgg-state-selected");
		// select the correct tab
		$(this).parent().addClass("elgg-state-selected");

		// hide all content
		$("#group-tools-special-states-featured, #group-tools-special-states-auto-join, #group-tools-special-states-suggested").addClass("hidden");
		// show the selected content
		$($(this).attr("href")).removeClass("hidden");

		return false;
	});

	$("#group-tools-special-states-featured a.elgg-requires-confirmation, #group-tools-special-states-auto-join a.elgg-requires-confirmation, #group-tools-special-states-suggested a.elgg-requires-confirmation").live("click", function(){

		elgg.action($(this).attr("href"));

		$(this).parent().parent().remove();
		
		return false;
	});

	$("#group-tools-admin-bulk-delete input[name='checkall'][type='checkbox']").live("change", function() {

		if ($(this).is(":checked")) {
			// check
			$("#group-tools-admin-bulk-delete input[name='group_guids[]']").attr("checked", "checked");
		} else {
			// uncheck
			$("#group-tools-admin-bulk-delete input[name='group_guids[]']").removeAttr("checked");
		}
	});
}

//register init hook
elgg.register_hook_handler("init", "system", elgg.group_tools_admin.init);