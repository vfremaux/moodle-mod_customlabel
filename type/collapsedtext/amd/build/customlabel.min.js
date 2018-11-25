// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

// jshint unused: true, undef:true

define(['jquery', 'core/log', 'core/config'], function($, log, cfg) {

    customboxcollapsed = {

        init: function() {
            $('.custombox-collapsedtext-open-all').bind('click', this.openall);
            $('.custombox-collapsedtext-close-all').bind('click', this.closeall);
            $('.custombox-collapsedtext-toggle').bind('click', this.togglestate);
            $('.custombox-collapsedtext-caption').bind('click', this.togglestateproxy);

            log.debug('AMD customlabel collapsedtext initialized.');
        },

        openall: function() {

            var that = $(this);

            var id = that.attr('id').replace('custombox-collapsedtext-open-all-', '');
            var collapsehandleclass = '.custombox-collapsedtext-toggle-' + id;
            var collapseclass = '.custombox-collapsedtext-panel-' + id
            $(collapseclass).removeClass('collapsed');
            $(collapsehandleclass + ' img').each(function() {
                var src = $(this).attr('src');
                $(this).attr('src', src.replace('closed', 'open'));
            });

            // Send data to completion.
            var url = cfg.wwwroot + '/mod/customlabel/type/collapsedtext/ajax/service.php';
            url += '?what=complete';
            url += '&cmid=' + id;

            $.get(url);
        },

        closeall: function(e) {

            var that = $(this);

            var id = that.attr('id').replace('custombox-collapsedtext-close-all-', '');
            var collapsehandleclass = '.custombox-collapsedtext-toggle-' + id;
            var collapseclass = '.custombox-collapsedtext-panel-' + id
            $(collapseclass).addClass('collapsed');
            $(collapsehandleclass + ' img').each(function() {
                var src = $(this).attr('src');
                $(this).attr('src', src.replace('open', 'closed'));
            });
        },

        togglestate: function(e) {
            var that = $(this);
            var toggle = that.hasClass('is-toggle');

            var fullid = that.attr('id').replace('custombox-collapsedtext-toggle-', '');
            var parts = fullid.split('-');
            var cmid = parts.shift();
            var itemid = parts.shift();
            var collapsehandleid = '#custombox-collapsedtext-toggle-' + fullid;
            var collapseid = '#custombox-collapsedtext-panel-' + fullid;
            var collapsehandleclass = '.custombox-collapsedtext-toggle-' + cmid;
            var collapseclass = '.custombox-collapsedtext-panel-' + cmid;

            if (toggle) {
                if ($(collapseid).hasClass('collapsed')) {
                    $(collapseid).removeClass('collapsed');
                    customboxcollapsed.sendcompletion(cmid, itemid);
                } else {
                    $(collapseid).addClass('collapsed');
                }
            } else {
                // Accordion behaviour. (close all before open).
                $(collapseclass).addClass('collapsed');
                $(collapseid).removeClass('collapsed');
                customboxcollapsed.sendcompletion(cmid, itemid);

                $(collapsehandleclass + ' img').each(function() {
                    var src = $(this).attr('src');
                    $(this).attr('src', src.replace('open', 'closed'));
                });
                var src = $(collapsehandleid + ' img').attr('src');
                $(collapsehandleid + ' img').attr('src', src.replace('closed', 'open'));
            }

            e.preventDefault();
        },

        togglestateproxy: function() {

            var that = $(this);

            var toggleid = that.attr('id').replace('custombox-collapsedtext-caption-', 'custombox-collapsedtext-toggle-');
            $('#' + toggleid).trigger('click');

        },

        sendcompletion: function(cmid, item) {
            // Send data to completion.
            var url = cfg.wwwroot + '/mod/customlabel/type/collapsedtext/ajax/service.php';
            url += '?what=open';
            url += '&cmid=' + cmid;
            url += '&item=' + item;

            $.get(url);
        }
    };

    return customboxcollapsed;

});