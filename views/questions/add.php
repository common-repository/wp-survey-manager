<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
 * Listing of WPSimple Backup
 */

$response = [];
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'addnewquestion')) {
    $response = Wp_Survey\Question\WpSurveyQuestionClass::addQuestion();
}
?>
<div id="overlay"><div id="overlaytext"></div></div>
<div class = "wrap">
    <h1 class="wp-heading-inline">Add New Question</h1>
    <?php if (array_key_exists('success', $response)): ?>
        <div class="alert alert-success surveydismiss">
            <p><?php echo $response['success']; ?></p>
        </div>
    <?php endif; ?>
    <div id="code" class="margintop20">
        <form id="addsurveyform" action="" method="post">
            <input type="hidden" name="action" value="addnewquestion" />
            <div class="form-group">
                <label for="surv_ques_required">Question Required ?: &nbsp;&nbsp;<input type="checkbox" class="form-control fixalign" id="surv_ques_required" name="surv_ques_required" value="1" <?php
                    if (array_key_exists('surv_ques_required', $_POST)) {
                        echo (1 == $_POST['surv_ques_required']) ? 'checked' : '';
                    }
                    ?>/> <span class="normaltxt"> Yes</span> </label>

                <?php if (array_key_exists('surv_ques_required', $response)): ?>
                    <p class="description surveyerror"><?php echo $response['surv_ques_required']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="surv_ques_title">Question:</label>
                <textarea class="form-control" id="surv_ques_title" name="surv_ques_title" placeholder="Enter Question" rows="5"><?php echo array_key_exists('surv_ques_title', $_POST) ? $_POST['surv_ques_title'] : ''; ?></textarea>
                <?php if (array_key_exists('surv_ques_title', $response)): ?>
                    <p class="description surveyerror"><?php echo $response['surv_ques_title']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="surv_ques_helptext">Question Help Text:</label>
                <textarea class="form-control" id="surv_ques_helptext" name="surv_ques_helptext" placeholder="Enter Question Help Text" rows="5"><?php echo array_key_exists('surv_ques_helptext', $_POST) ? $_POST['surv_ques_helptext'] : ''; ?></textarea>
                <?php if (array_key_exists('surv_ques_helptext', $response)): ?>
                    <p class="description surveyerror"><?php echo $response['surv_ques_helptext']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="surv_ques_type">Question Type:</label>
                <select class="form-control" id="surv_ques_type" name="surv_ques_type" placeholder="Select Question Type">
                    <option value="0" >Select Question Type</option>
                    <?php
                    foreach (Wp_Survey\Helper\WpSurveyHelperClass::getQuestionTypes() as $key => $type):
                        ?>
                        <option value="<?php echo $key; ?>" <?php
                        if (array_key_exists('surv_ques_type', $_POST)) {
                            echo ($key === $_POST['surv_ques_type']) ? 'selected' : '';
                        }
                        ?>><?php echo $type; ?></option>
                            <?php endforeach; ?>
                </select>
                <?php if (array_key_exists('surv_ques_type', $response)): ?>
                    <p class="description surveyerror"><?php echo $response['surv_ques_type']; ?></p>
                <?php endif; ?>
            </div>
            <?php $style = (array_key_exists('optionitem', $_POST)) ? 'style="display:block"' : 'style="display:none"'; ?>
            <div class="form-group questionoptions" <?php echo $style; ?>>
                <label for="surv_ques_type">Question Options:</label>
                <div class="optionitems">
                    <?php
                    $addButton = true;
                    if (array_key_exists('optionitem', $_POST)):
                        if (is_array($_POST['optionitem'])):
                            foreach ($_POST['optionitem'] as $key => $item):
                                $style = ($key == 0) ? 'style="width:100% !important"' : '';
                                $button = ($key == 0) ? '' : '<a href="#" class="button remove_field">Remove</a>';
                                ?>
                                <div class="optionrow">
                                    <input type="text" name="optionitem[]" class="optionitem form-control buttonmargin" placeholder="Enter Option Label" value="<?php echo $item; ?>" <?php echo $style; ?> required /><?php echo $button; ?>
                                    <?php if (array_key_exists('optionitem', $response) && array_key_exists($key, $response['optionitem'])): ?>
                                        <p class="description surveyerror"><?php echo $response['optionitem'][$key]; ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php
                            endforeach;
                        else:
                            $addButton = false;
                            ?>
                            <div class="optionrow">
                                <input type="number" name="optionitem" class="optionitem form-control buttonmargin" placeholder="Select Max Range" min="1" max="10" value="<?php echo $_POST['optionitem']; ?>" required style="width:100% !important;" />
                                <?php if (array_key_exists('optionitem', $response)): ?>
                                    <p class="description surveyerror"><?php echo $response['optionitem']; ?></p>
                                <?php endif; ?>
                            </div>
                        <?php
                        endif;
                    endif;
                    ?>
                </div>
                <button type="button" name="addmore" class="addoption button" <?php echo ($addButton) ? 'style="display:block;"' : 'style="display:none;"'; ?>>Add More</button>
            </div>
            <div class="clearfix"></div>
            <?php submit_button(); ?>
        </form>
    </div>
</div>
