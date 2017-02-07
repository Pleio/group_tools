<?php
$num = $vars['entity']->num_display;

$user = elgg_get_logged_in_user_entity();
if (!$user) {
    return;
}

$options = array(
    'type' => 'group',
    'relationship' => 'member',
    'relationship_guid' => $user->guid,
    'limit' => $num,
    'full_view' => FALSE,
    'pagination' => FALSE,
);
$content = elgg_list_entities_from_relationship($options);

echo $content;

if ($content) {
    $url = "groups/member/" . $user->username;
    $more_link = elgg_view('output/url', array(
        'href' => $url,
        'text' => elgg_echo('groups:more'),
        'is_trusted' => true,
    ));
    echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
    echo elgg_echo('groups:none');
}
