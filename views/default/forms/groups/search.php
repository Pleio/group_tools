<?php
/**
 * Group search form
 *
 * @uses $vars['entity'] ElggGroup
 */

$params = array(
    'name' => 'q',
    'class' => 'elgg-input-search mbm',
    'value' => $tag_string,
);
echo elgg_view('input/text', $params);

echo elgg_view('input/hidden', array(
    'name' => 'container_guid',
    'value' => $vars['entity']->getGUID(),
));

if ($vars['entity_type']) {
    echo elgg_view('input/hidden', array(
        'name' => 'search_type',
        'value' => 'entities',
    ));
    echo elgg_view('input/hidden', array(
        'name' => 'entity_type',
        'value' => $vars['entity_type'],
    ));
}

echo elgg_view('input/submit', array('value' => elgg_echo('search:go')));
