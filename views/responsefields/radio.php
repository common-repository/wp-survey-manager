<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$options = unserialize($question->surv_ques_options);
?>
<div class="form-group">
    <?php
    $posted = (array_key_exists($question->surv_ques_id, $response)) ? $response[$question->surv_ques_id] : '';
    ?>
    <?php foreach ($options as $option): ?>
        <label class="radio-inline"><input type="radio" name="<?php echo 'data[' . $question->surv_ques_id . ']'; ?>" value="<?php echo $option; ?>" <?php echo ($option === $posted) ? 'checked' : ''; ?> readonly disabled><?php echo $option; ?></label>
        <?php endforeach; ?>
</div>

