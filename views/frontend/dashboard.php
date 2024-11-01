<?php
/**
 * Template Name: Survey Dashboard
 * 
 */
if (true === is_user_logged_in()):
    ?>
    <table>
        <caption>Your Surveys</caption>
        <thead>
            <tr>
                <th scope="col">Survey Title</th>
                <th scope="col">Expiry Date</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $surveys = Wp_Survey\Survey\WpSurveyClass::getLoggedInUserSurvey();
            if (!empty($surveys)):
                foreach ($surveys as $survey):
                    ?>
                    <tr>
                        <td data-label="Title"><?php echo $survey->surv_title; ?></td>
                        <td data-label="Expiry Date"><?php echo date('F j, Y', strtotime($survey->surv_expiry_date)); ?></td>
                        <td data-label="Status"><?php echo (true === $survey->survey_taken) ? 'Completed' : 'Pending'; ?></td>
                        <td data-label="Action"><a href="<?php echo get_permalink() . 'view-survey/' . $survey->surv_id; ?>"><?php echo (true === $survey->survey_taken) ? 'View Response' : 'Respond'; ?></a></td>
                    </tr>
                    <?php
                endforeach;
            else:
                ?>
                <tr>
                    <td colspan="4">No Surveys Found.</td>
                </tr>
            <?php
            endif;
            ?>
        </tbody>
    </table>
    <?php
else:
    ?>
    <caption><center>You need to <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">Login</a> In order to view this page.</center></caption>
<?php
endif;
?>

