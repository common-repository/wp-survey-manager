<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
 * Listing of WPSimple Backup
 */

//Get Survey 
$survey = new stdClass();
if (isset($_REQUEST['survey']) && ($_REQUEST['survey'] > 0) && (filter_var($_REQUEST['survey'], FILTER_VALIDATE_INT))) {
    $survey = Wp_Survey\Survey\WpSurveyClass::getSurvey($_REQUEST['survey']);
} else {
    wp_redirect(menu_page_url('wp-survey-manager/views/surveys/listing.php'));
}


$response = [];
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'editsurvey')) {
    $response = Wp_Survey\Survey\WpSurveyClass::editSurvey();
}
?>
<div id="overlay"><div id="overlaytext"></div></div>
<div class = "wrap">
    <h1 class="wp-heading-inline">Edit Survey</h1>
    <?php if (array_key_exists('success', $response)): ?>
        <div class="alert alert-success surveydismiss">
            <p><?php echo $response['success']; ?></p>
        </div>
    <?php endif; ?>
    <div id="code" class="margintop20">
        <form id="editsurveyform" action="" method="post">
            <input type="hidden" name="action" value="editsurvey" />
            <input type="hidden" name="surveyid" value="<?php echo $survey->surv_id; ?>" />
            <div class="form-group">
                <label for="surv_title">Survey Title:</label>
                <input type="text" class="form-control" id="surv_title" placeholder="Enter survey title" name="surv_title" value="<?php echo array_key_exists('surv_title', $_POST) ? $_POST['surv_title'] : $survey->surv_title; ?>">
                <?php if (array_key_exists('surv_title', $response)): ?>
                    <p class="description surveyerror"><?php echo $response['surv_title']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="surv_description">Survey Description:</label>
                <textarea class="form-control" id="surv_description" name="surv_description" placeholder="Enter survey description" rows="10"><?php echo array_key_exists('surv_description', $_POST) ? $_POST['surv_description'] : $survey->surv_description; ?></textarea>
                <?php if (array_key_exists('surv_description', $response)): ?>
                    <p class="description surveyerror"><?php echo $response['surv_description']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="surv_expiry_date">Survey Expiry Date:</label>
                <input type="text" class="form-control" id="surv_expiry_date" name="surv_expiry_date" placeholder="Select expiry date" value="<?php echo array_key_exists('surv_expiry_date', $_POST) ? $_POST['surv_expiry_date'] : $survey->surv_expiry_date; ?>">
                <?php if (array_key_exists('surv_expiry_date', $response)): ?>
                    <p class="description surveyerror"><?php echo $response['surv_expiry_date']; ?></p>
                <?php endif; ?>
            </div>
            <?php
            $questions = Wp_Survey\Question\WpSurveyQuestionClass::getAllQuestions($filter = ['status' => 1]);
            if (!empty($questions)):
                ?>
                <label for="questions">Select Questions:</label>
                <select id="questions" class="multiselect form-control-custom" multiple="multiple" name="questionsarr[]">
                    <?php foreach ($questions as $question): ?>
                        <option value="<?php echo $question->surv_ques_id; ?>" <?php echo (in_array($question->surv_ques_id, Wp_Survey\Survey\WpSurveyClass::getSurveyQuestionMapping($survey->surv_id))) ? 'selected' : ''; ?>><?php echo $question->surv_ques_title; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php
            endif;
            ?>
            <?php
            //For filter
            $args = [];
            $users = get_users();
            if (!empty($users)):
                ?>
                <label for="users">Share With Users:</label>
                <select id="users" class="multiselect form-control-custom" multiple="multiple" name="users[]">
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user->data->ID; ?>" <?php echo (in_array($user->data->ID, Wp_Survey\Survey\WpSurveyClass::getSurveyUserMapping($survey->surv_id))) ? 'selected' : ''; ?>><?php echo $user->data->user_email . ' ( ' . $user->data->display_name . ' )'; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php
            endif;
            ?>
            <?php submit_button(); ?>
        </form>
    </div>
</div>
