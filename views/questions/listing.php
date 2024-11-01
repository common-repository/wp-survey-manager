<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
 * Listing of Survey Questions
 */

//Backup Code
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'deletequestion') && (!empty($_REQUEST['question']))) {
    $response = Wp_Survey\Question\WpSurveyQuestionClass::deleteQuestion($_REQUEST['question']);
}
?>
<div id="overlay"><div id="overlaytext"></div></div>
<div class = "wrap">
    <h1 class="wp-heading-inline">Survey Questions</h1>
    <a href="<?php menu_page_url('wp-survey-manager/views/questions/add.php', true); ?>" class="page-title-action">Add New</a>
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
                    <th>Question</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Author</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $questions = Wp_Survey\Question\WpSurveyQuestionClass::getAllQuestions();
                if (!empty($questions)):
                    foreach ($questions as $questionObject):
                        ?>
                        <tr>
                            <td><?php echo wp_trim_words($questionObject->surv_ques_title, 5); ?></td>
                            <td><?php echo $questionObject->surv_ques_type; ?></td>
                            <td><?php echo ($questionObject->surv_ques_status == 1) ? 'Active' : 'Deleted'; ?></td>
                            <td><?php echo ucfirst($questionObject->created_by_name); ?></td>
                            <td><?php echo date('F j, Y, g:i a', strtotime($questionObject->surv_ques_created)); ?></td>
                            <td><a href="<?php echo admin_url('admin.php?page=wp-survey-manager/views/questions/edit.php&question=' . $questionObject->surv_ques_id); ?>"><button class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" title="Edit"><span class="glyphicon glyphicon-edit"></span></button></a>&nbsp;&nbsp;<button class="btn btn-default btn-xs deletesurvey" onclick="window.location.href = document.URL + '&action=deletequestion&question=<?php echo $questionObject->surv_ques_id; ?>'" data-toggle="tooltip" data-placement="top" title="Delete"><span class="glyphicon glyphicon-trash"></span></button></td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>

    </div>
</div>
