<?php

/**
 * Fired when the plugin is uninstalled. 
 */
// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}surveys`");
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}survey_questions`");
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}survey_question_mapping`");
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}survey_response`");
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}survey_user_mapping`");
