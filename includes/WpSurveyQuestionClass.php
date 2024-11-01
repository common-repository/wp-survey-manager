<?php

/**
 * WpSimpleBackupHelper Class Helper utilities.
 *
 * @author kapil
 */

namespace Wp_Survey\Question;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WpSurveyQuestionClass {

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
        WpSurveyQuestionClass::$fillable = [
            'surv_ques_title' => 'Question',
            'surv_ques_type' => 'Question Type',
            'optionitem' => 'Question Option'
        ];
    }

    /**
     * Get Singleton Instance
     * 
     * @since   1.0
     */
    public static function getInstance() {

        if (empty(self::$instance)) {
            self::$instance = new WpSurveyQuestionClass();
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
    public static function addQuestion() {

        global $wpdb;

        $allowedPattern = "/[^A-Za-z0-9_-\s]/";

        //Sanitize
        if (array_key_exists('optionitem', $_POST)) {

            if (is_array($_POST['optionitem'])) {
                foreach ($_POST['optionitem'] as $key => $item) {
                    $_POST['optionitem'][$key] = trim(preg_replace($allowedPattern, "", $item));
                }
            }
        }
        if (array_key_exists('surv_ques_title', $_POST)) {
            $_POST['surv_ques_title'] = trim(preg_replace($allowedPattern, "", $_POST['surv_ques_title']));
        }
        if (array_key_exists('surv_ques_helptext', $_POST)) {
            $_POST['surv_ques_helptext'] = trim(preg_replace($allowedPattern, "", $_POST['surv_ques_helptext']));
        }

        //Validate
        foreach (WpSurveyQuestionClass::$fillable as $key => $label) {

            if (array_key_exists($key, $_POST) && empty($_POST[$key])) {
                WpSurveyQuestionClass::$errors[$key] = $label . ' is required';
            }

            if (array_key_exists($key, $_POST) && is_array($_POST[$key])) {
                foreach ($_POST[$key] as $k => $item) {
                    if (empty($_POST[$key][$k])) {
                        WpSurveyQuestionClass::$errors[$key][$k] = $label . ' is required';
                    }
                }
            }
        }

        if (count(WpSurveyQuestionClass::$errors) > 0) {
            return WpSurveyQuestionClass::$errors;
        }

        //Store
        $data = [
            'surv_ques_title' => filter_var($_POST['surv_ques_title'], FILTER_SANITIZE_STRING),
            'surv_ques_helptext' => filter_var($_POST['surv_ques_helptext'], FILTER_SANITIZE_STRING),
            'surv_ques_type' => filter_var($_POST['surv_ques_type'], FILTER_SANITIZE_STRING),
            'surv_ques_options' => (array_key_exists('optionitem', $_POST)) ? serialize($_POST['optionitem']) : serialize([]),
            'surv_ques_required' => (array_key_exists('surv_ques_required', $_POST)) ? 1 : 0,
            'surv_ques_status' => 1,
            'surv_ques_created_by' => get_current_user_id(),
            'surv_ques_updated_by' => get_current_user_id(),
            'surv_ques_created' => date('Y-m-d H:i:s'),
            'surv_ques_updated' => date('Y-m-d H:i:s'),
        ];
        $type = [
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
            '%d',
            '%d',
            '%d',
            '%s',
            '%s',
        ];

        $wpdb->insert(
                $wpdb->prefix . 'survey_questions', $data, $type
        );

        //Reset 
        $_POST = [];

        //Response
        return $response = ['success' => 'Question Added Successfully!'];
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function editQuestion() {
        //Survey 
        if (false === array_key_exists('questionid', $_POST)) {
            return $response = ['error' => 'Error in Question update!'];
        }

        $question = $_POST['questionid'];

        global $wpdb;

        $allowedPattern = "/[^A-Za-z1-9_-\s]/";

        //Sanitize
        if (array_key_exists('optionitem', $_POST)) {

            if (is_array($_POST['optionitem'])) {
                foreach ($_POST['optionitem'] as $key => $item) {
                    $_POST['optionitem'][$key] = trim(preg_replace($allowedPattern, "", $item));
                }
            }
        }
        if (array_key_exists('surv_ques_title', $_POST)) {
            $_POST['surv_ques_title'] = trim(preg_replace($allowedPattern, "", $_POST['surv_ques_title']));
        }
        if (array_key_exists('surv_ques_helptext', $_POST)) {
            $_POST['surv_ques_helptext'] = trim(preg_replace($allowedPattern, "", $_POST['surv_ques_helptext']));
        }

        //Validate
        foreach (WpSurveyQuestionClass::$fillable as $key => $label) {

            if (array_key_exists($key, $_POST) && empty($_POST[$key])) {
                WpSurveyQuestionClass::$errors[$key] = $label . ' is required';
            }

            if (array_key_exists($key, $_POST) && is_array($_POST[$key])) {
                foreach ($_POST[$key] as $k => $item) {
                    if (empty($_POST[$key][$k])) {
                        WpSurveyQuestionClass::$errors[$key][$k] = $label . ' is required';
                    }
                }
            }
        }

        if (count(WpSurveyQuestionClass::$errors) > 0) {
            return WpSurveyQuestionClass::$errors;
        }

        //Store
        $data = [
            'surv_ques_title' => filter_var($_POST['surv_ques_title'], FILTER_SANITIZE_STRING),
            'surv_ques_helptext' => filter_var($_POST['surv_ques_helptext'], FILTER_SANITIZE_STRING),
            'surv_ques_type' => filter_var($_POST['surv_ques_type'], FILTER_SANITIZE_STRING),
            'surv_ques_options' => (array_key_exists('optionitem', $_POST)) ? serialize($_POST['optionitem']) : serialize([]),
            'surv_ques_required' => (array_key_exists('surv_ques_required', $_POST)) ? 1 : 0,
            'surv_ques_updated_by' => get_current_user_id(),
            'surv_ques_updated' => date('Y-m-d H:i:s'),
        ];
        $type = [
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
            '%d',
            '%s',
        ];

        $wpdb->update(
                $wpdb->prefix . 'survey_questions', $data, ['surv_ques_id' => $question], $type, ['%d']
        );

        //Response
        return $response = ['success' => 'Question Updated Successfully!'];
    }

    /**
     * Delete Question
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function deleteQuestion($question_id = null) {
        global $wpdb;

        if (empty($question_id)) {
            return $response = ['error' => 'Error in deleting qunestion!'];
        }

        //Delete Survey Question Mapping First
        $wpdb->delete($wpdb->prefix . 'survey_question_mapping', [
            'surv_ques_mapp_ques_id' => $question_id
                ], ['%d']);


        //Delete Question
        $wpdb->delete($wpdb->prefix . 'survey_questions', [
            'surv_ques_id' => $question_id
                ], ['%d']);

        return $response = ['success' => 'Question Deleted Successfully!'];
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function getQuestion($question) {
        global $wpdb;
        $result = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'survey_questions WHERE surv_ques_id =' . $question);
        if (empty($result)) {
            wp_redirect(menu_page_url('wp-survey-manager/views/questions/listing.php'));
        }
        return $result;
    }

    /**
     * Get All Questions
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function getAllQuestions($filter = []) {

        global $wpdb;
        $response = [];
        $query = 'SELECT * FROM ' . $wpdb->prefix . 'survey_questions WHERE 1=1';
        if (array_key_exists('status', $filter) && (!empty($filter['status']))) {
            $query .= ' AND surv_ques_status =' . $filter['status'];
        }
        $query .= ' order by surv_ques_id desc';
        $response = $wpdb->get_results($query);
        if (!empty($response)) {
            foreach ($response as $res) {
                $userInfo = get_user_by('ID', $res->surv_ques_created_by);
                $res->created_by_name = $userInfo->data->user_nicename;
            }
        }
        return $response;
    }

}
