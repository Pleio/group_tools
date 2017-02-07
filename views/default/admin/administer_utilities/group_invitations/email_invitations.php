<ul class="elgg-list">
    <?php foreach (group_tools_get_email_invitations() as $invitation): ?>
        <li class="pvs">
            <?php 
            $icon = elgg_view("output/img", [
                "src" => "_graphics/icons/user/defaultsmall.gif",
                "alt" => $invitation["email"]
            ]);

            $link = elgg_view("output/url", [
                "href" =>"/groups/requests/" . $invitation["group"]->guid,
                "text" => $icon
            ]);

            $summary = elgg_view("object/elements/summary", [
                "title" => $invitation["email"] . " " . elgg_echo("group_tools:for") . " " . $invitation["group"]->name,
                "subtitle" => elgg_view_friendly_time($invitation["time_created"])
            ]);

            echo elgg_view_image_block($link, $summary);
            ?>
        </li>
    <?php endforeach; ?>
</ul>