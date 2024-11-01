<?php

/**
 * WpSurveySettingClass handles settings of WPSimpleBackup Plugin.
 *
 * @author kapil
 */

namespace Wp_Survey\Settings;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WpSurveySettingClass {

    /**
     * Class Member Variables
     *
     * @since   1.0
     */
    private static $instance;

    /**
     * Class Constructor
     * 
     * @since   1.0
     */
    private function __construct() {

        add_action('admin_menu', array($this, 'addSettingPage'));
    }

    /**
     * Get Singleton Instance
     * 
     * @since   1.0
     */
    public static function getInstance() {

        if (empty(self::$instance)) {
            self::$instance = new WpSurveySettingClass();
        }

        return self::$instance;
    }

    /**
     * Register Main Plugin Page
     */
    public function addSettingPage() {
        add_menu_page(
                __('Surveys', 'textdomain'), 'Surveys', 'manage_options', 'wp-survey-manager/views/surveys/listing.php', '', 'dashicons-feedback', 201
        );
        add_submenu_page(
                '', __('Add New Survey', 'textdomain'), 'Add New Survey', 'manage_options', 'wp-survey-manager/views/surveys/add.php'
        );
        add_submenu_page(
                '', __('Edit Survey', 'textdomain'), 'Edit Survey', 'manage_options', 'wp-survey-manager/views/surveys/edit.php'
        );
        add_submenu_page(
                'wp-survey-manager/views/surveys/listing.php', __('Questions', 'textdomain'), 'Questions', 'manage_options', 'wp-survey-manager/views/questions/listing.php'
        );
        add_submenu_page(
                '', __('Add New Question', 'textdomain'), 'Add New Quesrion', 'manage_options', 'wp-survey-manager/views/questions/add.php'
        );
        add_submenu_page(
                '', __('Edit Question', 'textdomain'), 'Edit Quesrion', 'manage_options', 'wp-survey-manager/views/questions/edit.php'
        );
        add_submenu_page(
                '', __('Survey', 'textdomain'), 'Survey', 'manage_options', 'wp-survey-manager/views/surveys/survey.php'
        );
        add_submenu_page(
                '', __('Survey Response', 'textdomain'), 'Survey Response', 'manage_options', 'wp-survey-manager/views/surveys/response.php'
        );
        add_submenu_page(
                '', __('View Response', 'textdomain'), 'View Response', 'manage_options', 'wp-survey-manager/views/surveys/viewresponse.php'
        );
//        add_submenu_page(
//                'wp-survey-manager/views/surveys/listing.php', __('Settings', 'textdomain'), 'Settings', 'manage_options', 'wp-survey-manager/views/settings/setting.php'
//        );
    }

    /**
     * Save Settings
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function saveSettings() {
        if (array_key_exists('settings', $_POST)) {
            update_option('wpsurveysettings', $_POST['settings']);
        } else {
            update_option('wpsurveysettings', null);
        }
        return $response = ['success' => 'Settings updated Successfully!'];
    }

}
