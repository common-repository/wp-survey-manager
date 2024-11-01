<?php

/*
 * Plugin Name: Wp Survey Manager
 * Plugin URI: http://infobeans.com
 * Description: Wp Survey Manager is a plugin for managing survey.
 * Version: 1.0
 * Author: <a href="https://profiles.wordpress.org/lpkapil008">Kapil Yadav</a>
 * Author URI: http://infobeans.com/
 */

/**
 * WpSurvey Main Plugin class continas plugin Initialization methods.
 * 
 * @author kapil
 */

namespace Wp_Survey;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WpSurvey {

    /**
     * Class Member Variables
     *
     * @since   1.0
     */
    private static $instance;
    private $version;
    public static $backupDir;

    /**
     * Class Constructor
     *
     * @since   1.0
     */
    private function __construct() {
        ob_start();
        $this->version = '1.0';
        $this->getLoader();
        $this->installDb();

        add_shortcode('wpsurvey', array($this, 'surveyFrontDashboard'));
        add_shortcode('wpsurveyview', array($this, 'surveyFrontView'));
    }

    /**
     * Get AutoLoader
     *
     * @since   1.0
     */
    private function getLoader() {
        require_once __DIR__ . '/vendor/autoload.php';
        $this->loadInstances();
        $this->loadActions();
    }

    /**
     * load Instances
     *
     * @since   1.0
     */
    private function loadInstances() {
        Settings\WpSurveySettingClass::getInstance();
        Survey\WpSurveyClass::getInstance();
        Question\WpSurveyQuestionClass::getInstance();
    }

    /**
     * Install Db
     * @since   1.0
     */
    private static function installDb() {
        Db\WpSurveyDbClass::install();
    }

    /**
     * load Actions
     *
     * @since   1.0
     */
    private function loadActions() {
        add_action('admin_enqueue_scripts', array($this, 'loadAssets'));
    }

    /**
     * load Assets
     *
     * @since   1.0
     */
    public function loadAssets() {
        $pluginInfo = get_plugin_data(__FILE__);
        //Load plugin admin assets on specific pages only
        if (isset($_REQUEST['page']) && (in_array($_REQUEST['page'], [
                    $pluginInfo['TextDomain'] . '/views/surveys/listing.php',
                    $pluginInfo['TextDomain'] . '/views/surveys/add.php',
                    $pluginInfo['TextDomain'] . '/views/surveys/edit.php',
                    $pluginInfo['TextDomain'] . '/views/surveys/response.php',
                    $pluginInfo['TextDomain'] . '/views/surveys/viewresponse.php',
                    $pluginInfo['TextDomain'] . '/views/surveys/survey.php',
                    $pluginInfo['TextDomain'] . '/views/questions/listing.php',
                    $pluginInfo['TextDomain'] . '/views/questions/add.php',
                    $pluginInfo['TextDomain'] . '/views/questions/edit.php',
                    $pluginInfo['TextDomain'] . '/views/settings/setting.php',
                ]))) {


            wp_enqueue_style('wpsurveybootstrapcss', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css');
            wp_enqueue_style('wpsurveydatatablecss', plugin_dir_url(__FILE__) . 'assets/css/jquery.dataTables.min.css');
            wp_enqueue_style('wpsurveydatatablebootstrapcss', plugin_dir_url(__FILE__) . 'assets/css/dataTables.bootstrap.min.css');
            wp_enqueue_style('wpsurveyuimultiselectcss', plugin_dir_url(__FILE__) . 'assets/css/ui.multiselect.css');
            wp_enqueue_script('wpsurveybootstrapjs', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.min.js', array('jquery'));
            wp_enqueue_script('wpsurveydatatablejs', plugin_dir_url(__FILE__) . 'assets/js/jquery.dataTables.min.js', array('jquery'));
            wp_enqueue_script('wpsurveyvalitejs', plugin_dir_url(__FILE__) . 'assets/js/jquery.validate.js', array('jquery'));
            wp_enqueue_script('jquery-ui-widget', array('jquery'));
            wp_enqueue_script('wpsurveyuicoreext', plugin_dir_url(__FILE__) . 'assets/js/jquery-ui-fix.js', array('jquery'));
            wp_enqueue_script('wpsurveyuimultiselect', plugin_dir_url(__FILE__) . 'assets/js/ui.multiselect.js', array('jquery'));
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jquery-ui-css', plugin_dir_url(__FILE__) . 'assets/css/jquery-ui-fix.css');
            wp_enqueue_script('wpsurveyscriptjs', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'));
            wp_enqueue_style('wpsurveydatatablecss', plugin_dir_url(__FILE__) . 'assets/css/jquery.dataTables.min.css');
            wp_enqueue_style('wpsurveystyle', plugin_dir_url(__FILE__) . 'assets/css/style.css');
        }
    }

    /**
     * Get Singleton Instance
     * 
     * @since   1.0
     */
    public static function getInstance() {

        if (empty(self::$instance)) {
            self::$instance = new WpSurvey();
        }

        return self::$instance;
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    private function loadFrontAssets() {
        wp_enqueue_style('wpsurveyfrontcss', plugin_dir_url(__FILE__) . 'assets/css/front.css');
        wp_enqueue_script('wpsurveyfrontjs', plugin_dir_url(__FILE__) . 'assets/js/front.js', array('jquery'));
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public function surveyFrontDashboard() {
        $this->loadFrontAssets();
        require_once __DIR__ . '/views/frontend/dashboard.php';
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public function surveyFrontView() {
        $this->loadFrontAssets();
        require_once __DIR__ . '/views/frontend/view.php';
    }

    /**
     * Activation Tasks
     * 
     * @since   1.0
     */
    public function actiovationTasks() {
        $survey = get_page_by_path('survey');
        $viewsurvey = get_page_by_path('survey/view');

        if ($survey) {
            wp_delete_post($survey->ID, true);
        }
        if ($viewsurvey) {
            wp_delete_post($viewsurvey->ID, true);
        }

        $survey = wp_insert_post([
            'post_title' => 'Survey',
            'post_content' => '[wpsurvey]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'survey'
        ]);

        $viewsurvey = wp_insert_post([
            'post_title' => 'View Survey',
            'post_content' => '[wpsurveyview]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_parent' => $survey,
            'post_name' => 'view-survey'
        ]);
    }

    /**
     * deActivation Tasks
     * 
     * @since   1.0
     */
    public function deactiovationTasks() {
        $survey = get_page_by_path('survey');
        $viewsurvey = get_page_by_path('survey/view-survey');

        if ($survey) {
            wp_delete_post($survey->ID, true);
        }
        if ($viewsurvey) {
            wp_delete_post($viewsurvey->ID, true);
        }
    }

}

$instance = WpSurvey::getInstance();

//Activation
register_activation_hook(__FILE__, array($instance, 'actiovationTasks'));

//Deactivation
register_deactivation_hook(__FILE__, array($instance, 'deactiovationTasks'));
