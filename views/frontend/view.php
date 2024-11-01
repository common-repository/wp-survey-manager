<?php
/**
 * Template Name: View Survey
 * 
 */
global $wpdb, $wp_query, $page;

if ($wp_query->get('page') == '') {
    $surveyId = $page;
} else {
    $surveyId = intval(trim($wp_query->get('page')));
}

if ((empty($surveyId)) || (!is_user_logged_in())) {
    load_template(get_404_template());
    die();
}

$survey = new stdClass();
if (isset($surveyId) && ($surveyId > 0) && (filter_var($surveyId, FILTER_VALIDATE_INT))) :

    //Valid survey invitee
    $surveyAssigned = Wp_Survey\Survey\WpSurveyClass::checkUserSurveyAssigned($surveyId);

    //Get survey
    $survey = Wp_Survey\Survey\WpSurveyClass::getSurvey($surveyId);

    if ($survey) {
        //Survey Questions
        $questions = Wp_Survey\Survey\WpSurveyClass::getSurveyQuestions($survey->surv_id);

        //Survey User Response
        $response = Wp_Survey\Survey\WpSurveyClass::getSurveyUserResponse($survey->surv_id, get_current_user_id());
        if ($response) {
            $response = unserialize($response->surv_resp_data);
        }
    }

    if (false === $surveyAssigned):
        load_template(get_404_template());
        die();
    endif;

    if (empty($survey)):
        load_template(get_404_template());
        die();
    else:
        //Survey Expired
        $isExpired = Wp_Survey\Survey\WpSurveyClass::checkSurveyExpired($survey->surv_id);
        //Survey Taken
        $isTaken = Wp_Survey\Survey\WpSurveyClass::checkUserSurveyTaken($survey->surv_id);
        if (false === $isExpired && false === $isTaken):
            //Add response
            $response = [];
            if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'savesurveyresponse')) {
                $response = Wp_Survey\Survey\WpSurveyClass::saveSurveyReponse($survey->surv_id);
            }
            ?>
            <div id="overlay"><div id="overlaytext"></div></div>
            <div class="wrap">
                <h1 class="wp-heading-inline"></h1>
                <a href="<?php echo get_permalink(get_page_by_path('survey')); ?>" class="btn btn-default"> Back</a><br><br>
                <?php if (array_key_exists('error', $response)): ?>
                    <div class="alert alert-danger surveydismiss">
                        <p><?php echo $response['error']; ?></p>
                    </div>
                <?php endif; ?>
                <div id="code" class="margintop20">
                    <?php
                    echo '<h1 class="wp-heading-inline">' . ucfirst($survey->surv_title) . '</h1>';
                    if (!empty($survey->surv_description)):
                        echo '<p>' . $survey->surv_description . '</p>';
                    endif;
                    ?>
                    <?php
                    $questions = Wp_Survey\Survey\WpSurveyClass::getSurveyQuestions($survey->surv_id);
                    if (!empty($questions)):
                        ?>
                        <form action="" method="post">
                            <input type="hidden" name="action" value="savesurveyresponse">
                            <?php
                            $count = 1;
                            foreach ($questions as $question):
                                ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading"><strong><?php echo ($question->surv_ques_required == 1) ? '<span class="ques_req">* </span>' : ''; ?><?php echo 'Question (' . $count . '): '; ?><?php echo $question->surv_ques_title . ' ?'; ?></strong></div>
                                    <div class="panel-body">
                                        <?php
                                        switch ($question->surv_ques_type) {

                                            case 'text':
                                                include WP_PLUGIN_DIR.'/wp-survey-manager/views/fields/text.php';
                                                break;
                                            case 'textarea':
                                                include WP_PLUGIN_DIR.'/wp-survey-manager/views/fields/textarea.php';
                                                break;
                                            case 'range':
                                                include WP_PLUGIN_DIR.'/wp-survey-manager/views/fields/range.php';
                                                break;
                                            case 'radio':
                                                include WP_PLUGIN_DIR.'/wp-survey-manager/views/fields/radio.php';
                                                break;
                                            case 'checkbox':
                                                include WP_PLUGIN_DIR.'/wp-survey-manager/views/fields/checkbox.php';
                                                break;
                                        }
                                        ?>
                                    </div>
                                    <div class="panel-footer"><?php echo (!empty($question->surv_ques_helptext)) ? '<strong>Help:</strong> ' . $question->surv_ques_helptext : ''; ?></div>
                                </div><br>
                                <?php
                                $count++;
                            endforeach;
                            submit_button('Save Feedback', ['class' => 'button-primary']);
                            ?>
                        </form>
                        <?php
                    endif;
                    ?>
                </div>
            </div>
        <?php elseif (true === $isExpired):
            echo 'Survey has been expired.';
        elseif (true === $isTaken):
            ?>
            <h1 class="wp-heading-inline"><?php echo ucfirst($survey->surv_title); ?>: View Response</h1> 
            <p>Thank you for your feedback.</p>
            <a href="<?php echo get_permalink(get_page_by_path('survey')); ?>" class="btn btn-default"> Back</a><br><br>
            <?php
            $count = 1;
            foreach ($questions as $question):
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong><?php echo ($question->surv_ques_required == 1) ? '<span class="ques_req">* </span>' : ''; ?><?php echo 'Question (' . $count . '): '; ?><?php echo $question->surv_ques_title . ' ?'; ?></strong></div>
                    <div class="panel-body">
                        <?php
                        switch ($question->surv_ques_type) {

                            case 'text':
                                include WP_PLUGIN_DIR.'/wp-survey-manager/views/responsefields/text.php';
                                break;
                            case 'textarea':
                                include WP_PLUGIN_DIR.'/wp-survey-manager/views/responsefields/textarea.php';
                                break;
                            case 'range':
                                include WP_PLUGIN_DIR.'/wp-survey-manager/views/responsefields/range.php';
                                break;
                            case 'radio':
                                include WP_PLUGIN_DIR.'/wp-survey-manager/views/responsefields/radio.php';
                                break;
                            case 'checkbox':
                                include WP_PLUGIN_DIR.'/wp-survey-manager/views/responsefields/checkbox.php';
                                break;
                        }
                        ?>
                    </div>
                    <div class="panel-footer"><?php echo (!empty($question->surv_ques_helptext)) ? '<span class="glyphicon glyphicon-info-sign"></span> ' . $question->surv_ques_helptext : ''; ?></div>
                </div>
                <?php
                $count++;
            endforeach;
        endif;
    endif;
endif;
?>


