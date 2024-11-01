<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$range = unserialize($question->surv_ques_options);
?>
<div class="form-group">
    <label for="<?php echo $question->surv_ques_id; ?>"></label>
    <output for="foo" onforminput="value = foo.valueAsNumber;"></output>
    <input id="<?php echo $question->surv_ques_id; ?>" type ="range" name="<?php echo 'data[' . $question->surv_ques_id . ']'; ?>" min ="1" max="<?php echo $range; ?>" step ="1" value="<?php echo (array_key_exists($question->surv_ques_id, $response)) ? $response[$question->surv_ques_id] : '1'; ?>" readonly/>
</div>