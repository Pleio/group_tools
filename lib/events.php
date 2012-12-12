<?php

	function group_tools_join_group_event($event, $type, $params){
		global $NOTIFICATION_HANDLERS;
		
		static $auto_notification;
		
		// only load plugin setting once
		if(!isset($auto_notification)){
			$auto_notification = false;
			
			if(elgg_get_plugin_setting("auto_notification", "group_tools") == "yes"){
				$auto_notification = true;
			}
		}
		
		if(!empty($params) && is_array($params)){
			$group = elgg_extract("group", $params);
			$user = elgg_extract("user", $params);
			
			if(($user instanceof ElggUser) && ($group instanceof ElggGroup)) {
				if($auto_notification && !empty($NOTIFICATION_HANDLERS) && is_array($NOTIFICATION_HANDLERS)){
					// only auto subscribe to site and email notifications
					$auto_notification_handlers = array(
						"site",
						"email"
					);
					
					foreach($NOTIFICATION_HANDLERS as $method => $dummy){
						if(in_array($method, $auto_notification_handlers)){
							add_entity_relationship($user->getGUID(), "notify" . $method, $group->getGUID());
						}
					}
				}
				
				// cleanup invites 
				remove_entity_relationship($group->getGUID(), "invited", $user->getGUID());
				
				// and requests
				remove_entity_relationship($user->getGUID(), "membership_request", $group->getGUID());
				
				// cleanup email invitations
				$options = array(
					"annotation_name" => "email_invitation",
					"annotation_value" => group_tools_generate_email_invite_code($group->getGUID(), $user->email),
					"limit" => false
				);
				
				elgg_delete_annotations($options);
			}
		}
	}
	
	function group_tools_join_site_handler($event, $type, $relationship){
		
		if(!empty($relationship) && ($relationship instanceof ElggRelationship)){
			$user_guid = $relationship->guid_one;
			$site_guid = $relationship->guid_two;
			
			if($user = get_user($user_guid)){
				// add user to the auto join groups
				if($auto_joins = elgg_get_plugin_setting("auto_join", "group_tools")){
					$auto_joins = string_to_tag_array($auto_joins);
					
					// ignore access
					$ia = elgg_set_ignore_access(true);
					
					foreach ($auto_joins as $group_guid) {
						if(($group = get_entity($group_guid)) && ($group instanceof ElggGroup)){
							if($group->site_guid == $site_guid){
								// join the group
								groups_join_group($group, $user);
							}
						}
					}
					
					// restore access settings
					elgg_set_ignore_access($ia);
				}
				
				// auto detect email invited groups
				if($groups = group_tools_get_invited_groups_by_email($user->email, $site_guid)){
					foreach($groups as $group){
						// join the group
						groups_join_group($group, $user);
					}
				}
			}
		}
	}
	
	function group_tools_multiple_admin_group_leave($event, $type, $params){
	
		if(!empty($params) && is_array($params)){
			if(array_key_exists("group", $params) && array_key_exists("user", $params)){
				$entity = $params["group"];
				$user = $params["user"];
	
				if(($entity instanceof ElggGroup) && ($user instanceof ElggUser)){
					if(check_entity_relationship($user->getGUID(), "group_admin", $entity->getGUID())){
						return remove_entity_relationship($user->getGUID(), "group_admin", $entity->getGUID());
					}
				}
			}
		}
	}
	