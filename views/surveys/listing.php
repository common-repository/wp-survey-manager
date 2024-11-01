<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
 * Listing of WPSimple Backup
 */

//Backup Code
$response = [];
//Delete
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'deletesurvey') && (!empty($_REQUEST['survey']))) {
    $response = Wp_Survey\Survey\WpSurveyClass::deleteSurvey($_REQUEST['survey']);
}
?>
<div id="overlay"><div id="overlaytext"></div></div>
<div class = "wrap">
    <h1 class="wp-heading-inline">Surveys</h1>
    <a href="<?php menu_page_url('wp-survey-manager/views/surveys/add.php', true); ?>" class="page-title-action">Add New</a>
    <div id="code" class="margintop20">
        <?php
        if (!empty($response)):
            foreach ($response as $key => $res):
                ?>
                <div class="alert alert-<?php echo $key; ?> surveydismiss">
                    <?php echo $res; ?>
                </div>
                <?php
            endforeach;
        endif;
        ?>
        <table id="codelisting" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Survey Title</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th>Author</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $surveys = Wp_Survey\Survey\WpSurveyClass::getAllSurveys();
                if (!empty($surveys)):
                    foreach ($surveys as $survey):
                        ?>
                        <tr>
                            <td><?php echo $survey->surv_title; ?></td>
                            <td><?php echo date('F j, Y', strtotime($survey->surv_expiry_date)); ?></td>
                            <td><?php echo ($survey->surv_status == 1) ? 'Active' : 'Deleted'; ?></td>
                            <td><?php echo ucfirst($survey->created_by_name); ?></td>
                            <td><?php echo date('F j, Y, g:i a', strtotime($survey->surv_created)); ?></td>
                            <td><a href="<?php echo admin_url('admin.php?page=wp-survey-manager/views/surveys/edit.php&survey=' . $survey->surv_id); ?>"><button class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" title="Edit"><span class="glyphicon glyphicon-edit"></span></button></a>&nbsp;&nbsp;<button class="btn btn-default btn-xs deletesurvey" onclick="window.location.href = document.URL + '&action=deletesurvey&survey=<?php echo $survey->surv_id; ?>'" data-toggle="tooltip" data-placement="top" title="Delete"><span class="glyphicon glyphicon-trash"></span></button>&nbsp;&nbsp;<a href="<?php echo admin_url('admin.php?page=wp-survey-manager/views/surveys/survey.php&survey=' . $survey->surv_id); ?>"><button class="btn btn-default btn-xs viewsurvey" data-toggle="tooltip" data-placement="top" title="Take Survey"><span class="glyphicon glyphicon-eye-open"></span></button></a>&nbsp;&nbsp;<a href="<?php echo admin_url('admin.php?page=wp-survey-manager/views/surveys/response.php&survey=' . $survey->surv_id); ?>"><button class="btn btn-default btn-xs viewsurvey" data-toggle="tooltip" data-placement="top" title="View Response"><span class="glyphicon glyphicon-comment"></span></button></a></td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>

    </div>
</div>
