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
} else {
    wp_redirect(menu_page_url('wp-survey-manager/views/surveys/listing.php'));
}
?>
<div id="overlay"><div id="overlaytext"></div></div>
<div class = "wrap">
    <h1 class="wp-heading-inline"><?php echo ucfirst($survey->surv_title); ?>: Responses</h1><br><br>
    <a href="<?php echo admin_url('admin.php?page=wp-survey-manager/views/surveys/listing.php'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-step-backward"></span> Back</a><br><br>
    <div id="code" class="margintop20">
        <table id="responselisting" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Invitee Name</th>
                    <th>Response Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $surveyResponse = Wp_Survey\Survey\WpSurveyClass::getSurveyResponses($survey->surv_id);
                if (!empty($surveyResponse)):
                    foreach ($surveyResponse as $response):
                        ?>
                        <tr>
                            <td><?php echo $response->created_by_name; ?></td>
                            <td><?php echo date('F j, Y', strtotime($response->surv_resp_created)); ?></td>
                            <td><a href="<?php echo admin_url('admin.php?page=wp-survey-manager/views/surveys/viewresponse.php&survey=' . $survey->surv_id . '&user=' . get_current_user_id()); ?>"><button class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" title="View Response"><span class="glyphicon glyphicon-eye-open"></span></button></a></td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>
