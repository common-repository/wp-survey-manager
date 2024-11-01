<?php

/**
 * WpSurveyClass handles code backup related operations in WPSimpleBackup.
 *
 * @author kapil
 */

namespace Wp_Survey\Survey;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WpSurveyClass {

    /**
     * Class Member Variables
     *
     * @since   1.0
     */
    private static $instance, $errors, $fillable;

    /**
     * Class Constructor
     * 
     * @since   1.0
     */
    private function __construct() {
        WpSurveyClass::$fillable = [
            'surv_title' => 'Survey Title',
            'surv_description' => 'Survey Description',
            'surv_expiry_date' => 'Survey Expiration Date'
        ];
    }

    /**
     * Get Singleton Instance
     * 
     * @since   1.0
     */
    public static function getInstance() {

        if (empty(self::$instance)) {
            self::$instance = new WpSurveyClass();
        }

        return self::$instance;
    }

    /**
     * Add Survey
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function addSurvey() {

        global $wpdb;

        //Validation
        foreach (WpSurveyClass::$fillable as $key => $label) {
            if (empty($_POST[$key])) {
                WpSurveyClass::$errors[$key] = $label . ' is required';
            }
        }

        if (count(WpSurveyClass::$errors) > 0) {
            return WpSurveyClass::$errors;
        }


        //Store
        $data = [
            'surv_title' => filter_var($_POST['surv_title'], FILTER_SANITIZE_STRING),
            'surv_description' => filter_var($_POST['surv_description'], FILTER_SANITIZE_STRING),
            'surv_expiry_date' => date('Y-m-d', strtotime($_POST['surv_expiry_date'])),
            'surv_status' => 1,
            'surv_created_by' => get_current_user_id(),
            'surv_created' => date('Y-m-d H:i:s'),
            'surv_updated' => date('Y-m-d H:i:s')
        ];

        $type = [
            '%s',
            '%s',
            '%s',
            '%d',
            '%d',
            '%s',
            '%s',
        ];

        $survey_id = $wpdb->insert(
                $wpdb->prefix . 'surveys', $data, $type
        );


        //Add mapping 
        if (array_key_exists('questionsarr', $_POST)) {
            WpSurveyClass::addSurveyQuestionMapping($survey_id, $_POST['questionsarr']);
        }

        if (array_key_exists('users', $_POST)) {
            WpSurveyClass::addSurveyUserMapping($survey_id, $_POST['users']);
        }


        //Reset 
        $_POST = [];

        //Response
        return $response = ['success' => 'Survey Created Successfully!'];
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function getSurvey($survey) {
        global $wpdb;
        $result = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'surveys WHERE surv_id =' . $survey);
        if (empty($result)) {
                wp_redirect(menu_page_url('wp-survey-manager/views/surveys/listing.php'));
        }
        return $result;
    }

    /**
     * Edit Survey
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function editSurvey() {

        global $wpdb;

        //Survey 
        if (false === array_key_exists('surveyid', $_POST)) {
            return $response = ['error' => 'Error in survey update!'];
        }

        $survey = $_POST['surveyid'];

        foreach (WpSurveyClass::$fillable as $key => $label) {
            if (empty($_POST[$key])) {
                WpSurveyClass::$errors[$key] = $label . ' is required';
            }
        }

        if (count(WpSurveyClass::$errors) > 0) {
            return WpSurveyClass::$errors;
        }

        $wpdb->update(
                $wpdb->prefix . 'surveys', [
            'surv_title' => filter_var($_POST['surv_title'], FILTER_SANITIZE_STRING),
            'surv_description' => filter_var($_POST['surv_description'], FILTER_SANITIZE_STRING),
            'surv_expiry_date' => date('Y-m-d', strtotime($_POST['surv_expiry_date'])),
            'surv_status' => 1,
            'surv_created_by' => get_current_user_id(),
            'surv_updated' => date('Y-m-d H:i:s')
                ], ['surv_id' => $survey], [
            '%s',
            '%s',
            '%s',
            '%d',
            '%d',
            '%s',
                ], ['%d']
        );






        //Remove old mapping and add new mapping
        //Add mapping 
        if (array_key_exists('questionsarr', $_POST)) {
            WpSurveyClass::removeSurveyQuestionMapping($survey);
            WpSurveyClass::addSurveyQuestionMapping($survey, $_POST['questionsarr']);
        } else {
            WpSurveyClass::removeSurveyQuestionMapping($survey);
        }

        //Users
        if (array_key_exists('users', $_POST)) {
            WpSurveyClass::removeSurveyUserMapping($survey);
            WpSurveyClass::addSurveyUserMapping($survey, $_POST['users']);
        } else {
            WpSurveyClass::removeSurveyUserMapping($survey);
        }

        return $response = ['success' => 'Survey Updated Successfully!'];
    }

    /**
     * Delete Code Backup
     *
     * @since   1.0
     */
    public static function deleteSurvey($survey = null) {

        global $wpdb;

        if (empty($survey)) {
            return $response = ['error' => 'Error in deleting survey!'];
        }

        $wpdb->delete($wpdb->prefix . 'surveys', [
            'surv_id' => $survey
                ], ['%d']);

        return $response = ['success' => 'Survey Deleted Successfully!'];
    }

    /**
     * Get All Code Backup Files
     * 
     * @since   1.0
     */
    public static function getAllSurveys() {

        global $wpdb;

        $response = [];
        $response = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'surveys order by surv_id desc');
        if (!empty($response)) {
            foreach ($response as $res) {
                $userInfo = get_user_by('ID', $res->surv_created_by);
                $res->created_by_name = $userInfo->data->user_nicename;
            }
        }
        return $response;
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function getLoggedInUserSurvey() {
        global $wpdb;

        //Mapping
        $subquery = 'SELECT surv_user_mapp_surv_id FROM ' . $wpdb->prefix . 'survey_user_mapping where surv_user_mapp_user_id =' . get_current_user_id();
        $response = [];
        $response = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'surveys WHERE surv_id IN (' . $subquery . ') order by surv_id desc');

        if (!empty($response)) {
            foreach ($response as $res) {
                $userInfo = get_user_by('ID', $res->surv_created_by);
                $res->created_by_name = $userInfo->data->user_nicename;

                //Survey status field populate
                $status = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'survey_response WHERE surv_resp_surv_id=' . $res->surv_id . ' AND surv_resp_user_id=' . get_current_user_id());
                $res->survey_taken = (!empty($status)) ? true : false;
            }
        }
        return $response;
    }

    /**
     * Remove old survey question mapping
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function removeSurveyQuestionMapping($survey_id = null) {
        global $wpdb;

        $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'survey_question_mapping WHERE surv_ques_mapp_surv_id =' . $survey_id);
    }

    /**
     * Add survey question mapping
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function addSurveyQuestionMapping($survey_id = null, $question_ids = []) {

        global $wpdb;

        foreach ($question_ids as $question_id) {
            $data = [
                'surv_ques_mapp_surv_id' => $survey_id,
                'surv_ques_mapp_ques_id' => $question_id,
                'surv_ques_mapp_created_by' => get_current_user_id(),
                'surv_ques_mapp_created' => date('Y-m-d H:i:s')
            ];
            $type = [
                '%d',
                '%d',
                '%d',
                '%s'
            ];
            $wpdb->insert(
                    $wpdb->prefix . 'survey_question_mapping', $data, $type
            );
        }
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function getSurveyQuestionMapping($survey_id = null) {

        global $wpdb;

        $results = [];
        $results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'survey_question_mapping WHERE surv_ques_mapp_surv_id=' . $survey_id);
        if (!empty($results)) {
            $response = [];
            foreach ($results as $result) {
                $response[] = $result->surv_ques_mapp_ques_id;
            }
            return $response;
        }
        return $results;
    }

    /**
     * Remove old survey question mapping
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function removeSurveyUserMapping($survey_id = null) {
        global $wpdb;

        $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'survey_user_mapping WHERE surv_user_mapp_surv_id =' . $survey_id);
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function addSurveyUserMapping($survey_id = null, $user_ids = []) {
        global $wpdb;

        foreach ($user_ids as $user_id) {
            $data = [
                'surv_user_mapp_surv_id' => $survey_id,
                'surv_user_mapp_user_id' => $user_id,
                'surv_user_mapp_created_by' => get_current_user_id(),
                'surv_user_mapp_created' => date('Y-m-d H:i:s')
            ];
            $type = [
                '%d',
                '%d',
                '%d',
                '%s'
            ];
            $wpdb->insert(
                    $wpdb->prefix . 'survey_user_mapping', $data, $type
            );
        }
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function getSurveyUserMapping($survey_id = null) {

        global $wpdb;

        $results = [];
        $results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'survey_user_mapping WHERE surv_user_mapp_surv_id=' . $survey_id);
        if (!empty($results)) {
            $response = [];
            foreach ($results as $result) {
                $response[] = $result->surv_user_mapp_user_id;
            }
            return $response;
        }
        return $results;
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function getSurveyQuestions($survey_id) {

        global $wpdb;
        $response = [];
        $query = "SELECT sq.surv_ques_id,sq.surv_ques_title, sq.surv_ques_helptext, sq.surv_ques_type, sq.surv_ques_options, sq.surv_ques_required FROM " . $wpdb->prefix . "survey_question_mapping as sqm INNER JOIN " . $wpdb->prefix . "survey_questions as sq ON (sq.surv_ques_id = sqm.	surv_ques_mapp_ques_id ) WHERE sqm.surv_ques_mapp_surv_id = " . $survey_id;
        $response = $wpdb->get_results($query);
        return $response;
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function saveSurveyReponse($survey_id) {

        global $wpdb;

        //Get survey questions
        $questions = \Wp_Survey\Survey\WpSurveyClass::getSurveyQuestions($survey_id);

        //Validate required data and send response
        if (empty($questions)) {
            return $response = ['error' => 'No Questions Added in Survey!'];
        }

        //Required Validations
        foreach ($questions as $question) {

            if ($question->surv_ques_required == 1 && ((false === array_key_exists($question->surv_ques_id, $_POST['data'])) || (empty($_POST['data'][$question->surv_ques_id])))) {
                WpSurveyClass::$errors[$question->surv_ques_id] = 'Question ' . $question->surv_ques_id . ' is required';
            }
        }

        if (count(WpSurveyClass::$errors) > 0) {
            return WpSurveyClass::$errors;
        }


        //Proceed and store response
        $data = [
            'surv_resp_surv_id' => $survey_id,
            'surv_resp_data' => serialize($_POST['data']),
            'surv_resp_user_id' => get_current_user_id(),
            'surv_resp_created' => date('Y-m-d H:i:s')
        ];
        $type = [
            '%d',
            '%s',
            '%d',
            '%s'
        ];

        $wpdb->insert(
                $wpdb->prefix . 'survey_response', $data, $type
        );

        //Reset data
        $_POST = [];

        //Response
        wp_redirect(add_query_arg(['survey' => $survey_id, 'user' => get_current_user_id()], menu_page_url('wp-survey-manager/views/surveys/response.php')));
        exit;
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function getSurveyResponses($survey_id) {
        $response = [];
        global $wpdb;
        $response = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'survey_response WHERE surv_resp_surv_id= ' . $survey_id);
        if (!empty($response)) {
            foreach ($response as $res) {
                $userInfo = get_user_by('ID', $res->surv_resp_user_id);
                $res->created_by_name = ucfirst($userInfo->data->user_nicename);
            }
        }
        return $response;
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function getSurveyUserResponse($survey_id, $userid) {
        $response = [];
        global $wpdb;
        $response = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'survey_response WHERE surv_resp_surv_id= ' . $survey_id . ' AND surv_resp_user_id=' . $userid);
        if (!empty($response)) {
            $userInfo = get_user_by('ID', $response->surv_resp_user_id);
            $response->created_by_name = ucfirst($userInfo->data->user_nicename);
        }
        return $response;
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function checkUserSurveyTaken($survey_id) {
        global $wpdb;
        $result = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'survey_response WHERE surv_resp_surv_id= ' . $survey_id . ' AND surv_resp_user_id=' . get_current_user_id());
        if (empty($result)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function checkSurveyExpired($survey_id) {
        $survey = \Wp_Survey\Survey\WpSurveyClass::getSurvey($survey_id);
        $date_now = date("Y-m-d"); // this format is string comparable
        if ($date_now > $survey->surv_expiry_date) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check Survey Assigned to current user or not
     * 
     * @since   1.0
     */
    public static function checkUserSurveyAssigned($survey_id) {
        global $wpdb;
        $result = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'survey_user_mapping WHERE surv_user_mapp_surv_id= ' . $survey_id . ' AND surv_user_mapp_user_id=' . get_current_user_id());
        if (empty($result)) {
            return false;
        } else {
            return true;
        }
    }

}
