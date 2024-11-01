/* 
 * WPSimpleBackup Javascript Document
 */


(function ($) {

    //Overlay on
    function on(content) {
        var content = (!content) ? '<span class="glyphicon glyphicon-ok"></span> Loading..' : content;
        jQuery("#overlaytext").html(content);
        jQuery("#overlay").show();
    }

    //Overlay Off
    function off() {
        jQuery("#overlaytext").html('');
        jQuery("#overlay").hide();
    }

    jQuery(document).ready(function () {

        if (jQuery('#surv_expiry_date').length) {
            jQuery('#surv_expiry_date').datepicker({
                dateFormat: 'yy-mm-dd',
                minDate: 0
            });
        }

        jQuery('#codebackupbtn').click(function () {
            on('<br> <span class="glyphicon glyphicon-ok"></span> Saving Backup');
            jQuery('.nav-tabs a[href="#code"]').tab('show');
        });

        jQuery('#deletecodebackupbtn').click(function () {
            on('<br> <span class="glyphicon glyphicon-ok"></span> Deleting Backup');
            jQuery('.nav-tabs a[href="#code"]').tab('show');
        });


        jQuery('.codetab').click(function () {
            if (jQuery(this).parent().hasClass('active')) {
                window.location.href = window.location.href + '&tab=' + jQuery(this).attr('data-field');
            }
        });

        jQuery('#codelisting').DataTable({
            'processing': true,
            'bProcessing': true,
            'aaSorting': [[1, 'desc']],
            'columnDefs': [
                {orderable: false, targets: [5]}
            ]
        });

        //Response
        jQuery('#responselisting').DataTable({
            'processing': true,
            'bProcessing': true,
            'aaSorting': [[1, 'desc']],
            'columnDefs': [
                {orderable: false, targets: [2]}
            ]
        });

        //Autohide notice messages
        jQuery('.surveydismiss').delay(1000).fadeOut('slow');

        //Question Type
        var wrapper = jQuery(".optionitems"); //Fields wrapper
        var add_button = jQuery(".addoption"); //Add button ID
        jQuery('#surv_ques_type').change(function () {
            if ((jQuery(this).val() === 'checkbox') || (jQuery(this).val() === 'radio')) {
                console.log('radio & checkbox');
                jQuery(wrapper).html('');
                jQuery(add_button).show();
                jQuery(wrapper).append('<div class="optionrow"><input type="text" name="optionitem[]" class="optionitem form-control buttonmargin" placeholder="Enter Option Label" style="width:100% !important" required /></div>'); //add input box
                jQuery('.questionoptions').show();
            } else if ((jQuery(this).val() === 'range')) {
                console.log('range');
                jQuery(add_button).hide();
                jQuery(wrapper).html('');
                jQuery(wrapper).append('<div class="optionrow"><input type="number" name="optionitem" class="optionitem form-control buttonmargin" min="0" max="10" placeholder="Select Max Range" style="width:100% !important" /></div>'); //add input box
                jQuery('.questionoptions').show();
            } else {
                console.log(jQuery(this).val());
                jQuery(add_button).hide();
                jQuery(wrapper).html('');
                jQuery('.questionoptions').hide();
            }
        });

        //Remove option field
        jQuery(".optionitems").on("click", ".remove_field", function (e) {
            e.preventDefault();
            $(this).parent('div').remove();
        });

        // Add option field
        jQuery(add_button).click(function (e) { //on add input button click
            e.preventDefault();
            jQuery(wrapper).append('<div class="optionrow"><input type="text" name="optionitem[]" class="optionitem form-control buttonmargin" placeholder="Enter Option Label" required /><a href="#" class="button remove_field">Remove</a></div>'); //add input box
        });

        //Multiseelct
        $("#questions").multiselect({
            dividerLocation: 0.5,
            availableFirst: true,
        });
        $("#users").multiselect({
            dividerLocation: 0.5,
            availableFirst: true,
        });

        //Edit survey form
        var formadd = $("form#addsurveyform");
        var formedit = $("form#editsurveyform");
        $(formadd).on('submit', function (e) {
            $("ul.selected li").each(function () {
                var selected_value = $(this).attr('data-selected-value');
                if (selected_value) {
                    $(formadd).append("<input type='hidden' value='" + selected_value + "' name='questions[]' />");
                }
            });
        });
        $(formedit).on('submit', function (e) {
            $("ul.selected li").each(function () {
                var selected_value = $(this).attr('data-selected-value');
                if (selected_value) {
                    $(formedit).append("<input type='hidden' value='" + selected_value + "' name='questions[]' />");
                }
            });
        });

        //Range slider
        var el, newPoint, newPlace, offset;

        // Select all range inputs, watch for change
        $("input[type='range']").change(function () {

            // Cache this for efficiency
            el = $(this);

            // Measure width of range input
            width = el.width();

            // Figure out placement percentage between left and right of input
            newPoint = (el.val() - el.attr("min")) / (el.attr("max") - el.attr("min"));

            offset = -1;

            // Prevent bubble from going beyond left or right (unsupported browsers)
            if (newPoint < 0) {
                newPlace = 0;
            } else if (newPoint > 1) {
                newPlace = width;
            } else {
                newPlace = width * newPoint + offset;
                offset -= newPoint;
            }

            // Move bubble
            el.prev("output").css({
                left: newPlace,
                marginLeft: offset + "%"
            }).text(el.val());
        })
                // Fake a change to position bubble at page load
                .trigger('change');
    });
}(jQuery));