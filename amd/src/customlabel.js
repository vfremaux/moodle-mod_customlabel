/*
 *
 */
// jshint unused:false, undef:false

define(['jquery', 'core/config', 'core/log'], function($, cfg, log) {

    var customlabel = {

        init: function() {
            $('.customctl').bind('click', this.togglecustom);
            $('.customctl-string').bind('click', this.togglecustomstring);
            $('.customlabel-constraint').bind('click', this.constraint_colorize);

            log.debug("AMD Customlabels initialized");
        },

        togglecustom: function() {

            var that = $(this);

            var customthumbid = that.attr('id').replace('customctl-', 'custom-thumb-');
            var customid = that.attr('id').replace('customctl-', 'custom-');

            if ($('#' + customid).hasClass('hidden')) {
                $('#' + customid).removeClass('hidden');
                $('#' + customthumbid).removeClass('hidden');
                that.attr('src', cfg.wwwroot + '/mod/customlabel/pix/plus.gif');
            } else {
                $('#' + customid).addClass('hidden');
                $('#' + customthumbid).addClass('hidden');
                that.attr('src', cfg.wwwroot + '/mod/customlabel/pix/minus.gif');
            }
        },

        togglecustomstring: function() {

            var that = $(this);
            var customid = that.attr('id').replace('customctl-', 'custom-');
            var parts = that.attr('data').split(',');

            if ($('#' + customid).css('display') === 'block') {
                $('#' + customid).css('display', 'none');
                that.html(parts[1]);
            } else {
                $('#' + customid).css('display', 'block');
                that.html(parts[2]);
            }
        },

        constraint_colorize: function() {
            var that = $(this);

            if (that.val() == 1) {
                this.addclass('green');
            } else {
                this.addclass('red');
            }
        }
    };

    return customlabel;
});
