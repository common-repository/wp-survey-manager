<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$response = [];
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'wpsurveysettings')) {
    $response = Wp_Survey\Settings\WpSurveySettingClass::saveSettings();
}

$settings = get_option('wpsurveysettings', true);
?>
<div id="overlay"><div id="overlaytext"></div></div>
<div class = "wrap">
    <h1>Survey Settings</h1>
    <?php if (array_key_exists('success', $response)): ?>
        <div class="alert alert-success surveydismiss">
            <p><?php echo $response['success']; ?></p>
        </div>
    <?php endif; ?>
    <div id="check" class="margintop20">
        <form action="" method="post" style="display: none;">
            <input type="hidden" name="action" value="wpsurveysettings">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#general">General</a></li>
            </ul>
            <div class="tab-content">
                <div id="general" class="tab-pane fade in active margintop20">
                    
                </div>
            </div>
            <?php submit_button(); ?>
        </form>
    </div>
</div>

