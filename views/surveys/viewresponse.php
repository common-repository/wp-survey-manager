<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/*
 * View Resposne
 */
$survey = new stdClass();
if (isset($_REQUEST['survey']) && ($_REQUEST['survey'] > 0) && (filter_var($_REQUEST['survey'], FILTER_VALIDATE_INT))) {

    //Survey
    $survey = Wp_Survey\Survey\WpSurveyClass::getSurvey($_REQUEST['survey']);

    //Survey Questions
    $questions = Wp_Survey\Survey\WpSurveyClass::getSurveyQuestions($survey->surv_id);

    //Survey User Response
    $response = Wp_Survey\Survey\WpSurveyClass::getSurveyUserResponse($survey->surv_id, $_GET['user']);
    $response = unserialize($response->surv_resp_data);

    //User
    $user = get_user_by('ID', $_GET['user']);
} else {
    wp_redirect(menu_page_url('wp-survey-manager/views/surveys/listing.php'));
}
?>

<div id="overlay"><div id="overlaytext"></div></div>
<div class = "wrap">
    <h1 class="wp-heading-inline"><?php echo ucfirst($survey->surv_title); ?>: View Response</h1> (<?php echo $user->user_email; ?>)<br><br>
    <a href="<?php echo admin_url('admin.php?page=wp-survey-manager/views/surveys/response.php&survey=' . $survey->surv_id); ?>" class="btn btn-default"><span class="glyphicon glyphicon-step-backward"></span> Back</a><br><br>
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
    ?>
</div>