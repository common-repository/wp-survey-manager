<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$options = unserialize($question->surv_ques_options);
?>
<div class="form-group">
    <?php $posted = (array_key_exists('data', $_POST)) ? (array_key_exists($question->surv_ques_id, $_POST['data'])) ? $_POST['data'][$question->surv_ques_id] : [] : []; ?>
    <?php foreach ($options as $option): ?>
        <label class="checkbox-inline"><input type="checkbox" name="<?php echo 'data[' . $question->surv_ques_id . '][]'; ?>" value="<?php echo $option; ?>" <?php echo (in_array($option, $posted)) ? 'checked' : ''; ?>><?php echo $option; ?></label>
    <?php endforeach; ?>
    <?php if (array_key_exists($question->surv_ques_id, $response)): ?>
        <p class="description surveyerror"><?php echo $response[$question->surv_ques_id]; ?></p>
    <?php endif; ?>
</div>