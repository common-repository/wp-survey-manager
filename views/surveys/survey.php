<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
 * View Resposne
 */
$survey = new stdClass();
if (isset($_REQUEST['survey']) && ($_REQUEST['survey'] > 0) && (filter_var($_REQUEST['survey'], FILTER_VALIDATE_INT))) {

    $survey = Wp_Survey\Survey\WpSurveyClass::getSurvey($_REQUEST['survey']);

    //Survey Expired
    if (Wp_Survey\Survey\WpSurveyClass::checkSurveyExpired($survey->surv_id)) {
        wp_redirect(menu_page_url('wp-survey-manager/views/surveys/listing.php'));
    }
    //Survey Taken
    if (Wp_Survey\Survey\WpSurveyClass::checkUserSurveyTaken($survey->surv_id)) {
        wp_redirect(menu_page_url('wp-survey-manager/views/surveys/listing.php'));
    }
} else {
    wp_redirect(menu_page_url('wp-survey-manager/views/surveys/listing.php'));
}


//Add response
$response = [];
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'savesurveyresponse')) {
    $response = Wp_Survey\Survey\WpSurveyClass::saveSurveyReponse($survey->surv_id);
}
?>
<div id="overlay"><div id="overlaytext"></div></div>
<div class="wrap">
    <h1 class="wp-heading-inline"></h1>
    <a href="<?php echo admin_url('admin.php?page=wp-survey-manager/views/surveys/listing.php'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-step-backward"></span> Back</a><br><br>
    <?php if (array_key_exists('error', $response)): ?>
        <div class="alert alert-danger surveydismiss">
            <p><?php echo $response['error']; ?></p>
        </div>
    <?php endif; ?>
    <div class="well">
        <?php
        echo '<h1 class="wp-heading-inline">' . ucfirst($survey->surv_title) . '</h1>';
        if (!empty($survey->surv_description)):
            echo '<br>' . $survey->surv_description;
        endif;
        ?>
    </div>

    <div id="code" class="margintop20">
        <?php
        $questions = Wp_Survey\Survey\WpSurveyClass::getSurveyQuestions($_REQUEST['survey']);
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
                        <div class="panel-footer"><?php echo (!empty($question->surv_ques_helptext)) ? '<span class="glyphicon glyphicon-info-sign"></span> ' . $question->surv_ques_helptext : ''; ?></div>
                    </div>
                    <?php
                    $count++;
                endforeach;
                submit_button('Save Feedback');
                ?>
            </form>
            <?php
        endif;
        ?>
    </div>
</div>
