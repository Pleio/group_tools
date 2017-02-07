<?php
$title = elgg_echo("group_tools:groups:membershipreq:invitations");
echo elgg_view_module("inline", $title,
    elgg_view("admin/administer_utilities/group_invitations/invitations")
); 
?>

<?php
$title = elgg_echo("group_tools:groups:membershipreq:email_invitations");
echo elgg_view_module("inline", $title, 
    elgg_view("admin/administer_utilities/group_invitations/email_invitations")
); 
?>