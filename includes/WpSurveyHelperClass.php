<?php

/**
 * WpSimpleBackupHelper Class Helper utilities.
 *
 * @author kapil
 */

namespace Wp_Survey\Helper;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

abstract class WpSurveyHelperClass {

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public static function getQuestionTypes() {
        return ['checkbox' => 'CheckBox',
            'radio' => 'Radio',
            'range' => 'Range',
            'text' => 'Text',
            'textarea' => 'TextArea',
        ];
    }

}
