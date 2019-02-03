/*
 *
 */
// jshint unused:false, undef:false

define(['jquery', 'core/config', 'core/str', 'core/log'], function($, cfg, str, log) {

    var customlabel = {

        strs: [],

        context: [],

        init: function(params) {

            customlabel.context = params;

            // Call strings.
            var stringdefs = [
                {key: 'changetypeadvice', component: 'customlabel'}, // 0
            ];

            str.get_strings(stringdefs).done(function(s) {
                customlabel.strs = s;
            });

            $('.customctl').bind('click', this.togglecustom);
            $('.customctl-string').bind('click', this.togglecustomstring);
            $('.customlabel-constraint').bind('click', this.constraint_colorize);
            $('select.constrained').bind('change', this.applyconstraints);
            $('select.constrained').prop('disabled', null);
            $('.labeltypeselector').bind('change', this.typechangesubmit);

            log.debug("AMD Customlabels initialized");
        },

        rebindconstraints: function() {
            $('select.constrained').off('change');
            $('select.constrained').bind('change', this.applyconstraints);
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

            if ($('#' + customid).hasClass('hidden')) {
                $('#' + customid).removeClass('hidden');
                that.html(parts[1]);
            } else {
                $('#' + customid).addClass('hidden');
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
        },

        applyconstraints: function() {

            var that = $(this);
            var targets = that.attr('data-constraints');
            var labeltype = that.attr('data-label-type');
            var cmid = that.attr('data-cmid');

            var i,j;

            if (targets === '') {
                return;
            }
            var targetsarr = targets.split(',');

            var selectedopts = [];

            // Get constraints in activated select.
            i = 0;
            sourceseloptions = $('#' + that.attr('id') + " option");
            sourceseloptions.each(function() {
                if ($(this).prop('selected')) {
                    selectedopts.push($(this).val());
                }
            });

            var optionstring = selectedopts.join(',');

            // Get selection constraints in targets select.
            var selectedtargetopts = [];
            var targetelms = [];
            i = 0;

            var extractvalues = function() {
                selectedtargetopts[i].push($(this).val());
            };

            for (var target in targetsarr) {
                targetname = 'level' + targetsarr[target];
                selectedtargetopts[i] = targetname;
                targetelms.push($('#id_' + targetname).first());
                i++;
                selectedtargetopts[i] = [];

                targetseloptions = $('#id_' + targetname + " option:selected");
                targetseloptions.each(extractvalues);

                i++;
            }

            // Invalidate targets waiting for constraints resolution
            log.debug(targetelms);
            $.each(targetelms, function(index, value) {
                value.prop('disabled', true);
            });

            var formvaluestring = urlencode(JSON.stringify(selectedtargetopts));

            var url = cfg.wwwroot + "/mod/customlabel/ajax/applyconstraints.php?";
            url += "selector=" + that.attr('name');
            url += "&targets=" + targets;
            url += "&type=" + labeltype;
            url += "&cmid=" + cmid;
            url += "&constraints=" + optionstring;
            url += '&selection=' + formvaluestring;

            $.get(url, '', function(data, status) {
                // var selectors = JSON.parse(data);
                selectors = data;

                var targetsarr = targets.split(',');

                // Dispatch in selectors.
                for (var target in targetsarr) {
                    var targetname = 'level' + target;
                    if (selectors[targetsarr[target]]) {
                        var str = '<input type="hidden" name="level' + targetsarr[target];
                        str += '" value="_qf__force_multiselect_submission">';
                        str += selectors[targetsarr[target]];
                        $('#fitem_id_level' + targetsarr[target] + ' .felement.fselect').html(str);
                    }
                }
                customlabel.rebindconstraints();
            }, 'json');
        },

        typechangesubmit: function() {

            var that = $(this);
            var url;

            if (confirm(customlabel.strs[0])) {
                if (!customlabel.context.updatelabelid) {
                    url = cfg.wwwroot + '/course/mod.php?';
                    url += 'id=' + customlabel.context.courseid;
                    url += '&section=' + customlabel.context.section;
                    url += '&sesskey=' + cfg.sesskey;
                    url += '&add=customlabel';
                    url += '&returntomod=' + customlabel.context.returntomod;
                    url += '&type=' + that.val();
                } else {
                    url = cfg.wwwroot + '/mod/customlabel/mod.php?';
                    url += 'update=' + customlabel.context.updatelabelid;
                    url += '&sesskey=' + cfg.sesskey;
                    url += '&sr=' + customlabel.context.section;
                    url += '&returntomod=' + customlabel.context.returntomod;
                    url += '&type=' + that.val();
                }
                document.location.href = url;
            }
        }
    };

    return customlabel;
});
