<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="form-group">
    <label for="<?php echo $question->surv_ques_id; ?>"></label>
    <input class="form-control" id="<?php echo $question->surv_ques_id; ?>" name="<?php echo 'data[' . $question->surv_ques_id . ']'; ?>" type="text" value="<?php echo (array_key_exists('data', $_POST)) ? (array_key_exists($question->surv_ques_id, $_POST['data'])) ? $_POST['data'][$question->surv_ques_id] : '' : ''; ?>">
    <?php if (array_key_exists($question->surv_ques_id, $response)): ?>
        <p class="description surveyerror"><?php echo $response[$question->surv_ques_id]; ?></p>
    <?php endif; ?>
</div>  