<?php

/**
 * WpSurveyClass handles code backup related operations in WPSimpleBackup.
 *
 * @author kapil
 */

namespace Wp_Survey\Db;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WpSurveyDbClass {

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function install() {

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}surveys` (
                    `surv_id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `surv_title` varchar(255) NOT NULL,
                    `surv_description` text NOT NULL,
                    `surv_expiry_date` date NOT NULL,
                    `surv_status` tinyint(1) NOT NULL,
                    `surv_created_by` bigint(20) NOT NULL,
                    `surv_created` datetime NOT NULL,
                    `surv_updated` datetime NOT NULL,
                    PRIMARY KEY (`surv_id`)
                  ) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}survey_questions` (
                    `surv_ques_id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `surv_ques_title` text NOT NULL,
                    `surv_ques_helptext` text NOT NULL,
                    `surv_ques_type` varchar(255) NOT NULL,
                    `surv_ques_options` text NOT NULL,
                    `surv_ques_required` tinyint(1) NOT NULL,
                    `surv_ques_status` tinyint(1) NOT NULL,
                    `surv_ques_created_by` bigint(20) NOT NULL,
                    `surv_ques_updated_by` bigint(20) NOT NULL,
                    `surv_ques_created` datetime NOT NULL,
                    `surv_ques_updated` datetime NOT NULL,
                    PRIMARY KEY (`surv_ques_id`)
                  ) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}survey_question_mapping` (
                    `surv_ques_mapp_id` bigint(20) NOT NULL AUTO_INCREMENT,
                     `surv_ques_mapp_surv_id` bigint(20) NOT NULL,
                     `surv_ques_mapp_ques_id` bigint(20) NOT NULL,
                     `surv_ques_mapp_created_by` bigint(20) NOT NULL,
                     `surv_ques_mapp_created` datetime NOT NULL,
                     PRIMARY KEY (`surv_ques_mapp_id`)
                    ) ENGINE = InnoDB $charset_collate AUTO_INCREMENT = 1;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}survey_response` (
                    `surv_resp_id` bigint(20) NOT NULL AUTO_INCREMENT,
                     `surv_resp_surv_id` bigint(20) NOT NULL,
                     `surv_resp_data` text NOT NULL,
                     `surv_resp_user_id` bigint(20) NOT NULL,
                     `surv_resp_created` datetime NOT NULL,
                     PRIMARY KEY (`surv_resp_id`)
                    ) ENGINE = InnoDB $charset_collate AUTO_INCREMENT = 1;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}survey_user_mapping` (
                    `surv_user_mapp_id` bigint(20) NOT NULL AUTO_INCREMENT,
                     `surv_user_mapp_surv_id` bigint(20) NOT NULL,
                     `surv_user_mapp_user_id` bigint(20) NOT NULL,
                     `surv_user_mapp_created_by` bigint(20) NOT NULL,
                     `surv_user_mapp_created` datetime NOT NULL,
                     PRIMARY KEY (`surv_user_mapp_id`)
                    ) ENGINE = InnoDB $charset_collate AUTO_INCREMENT = 1;";
        dbDelta($sql);
    }

}
