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

// jshint undef:false, unused:false, scripturl:true

/**
 * @module     customlabeltype_verticalspacer
 * @package    customlabeltype
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/config', 'core/log'], function($, cfg, log) {

    var customboxverticalspacer = {

        catched: 0,

        verticalspacerlocation: [],

        verticalspacerpagelocation: [],

        init: function() {
            $('.custombox-verticalspacer-handle').off();
            $('.custombox-verticalspacer-handle').bind('mousedown', this.mousedown);
            $('.custombox-verticalspacer-handle').bind('mouseup', this.mouseup);
            $('.custombox-verticalspacer-handle').bind('mousemove', this.mousemove);

            log.debug('AMD customlabel verticalspacer initialized');
        },

        mousedown: function(e) {

            var that = $(this);
            log.debug('Catching ' + that.attr('id'));
            var fullid = that.attr('id').replace('verticalspacer-handle-', '');
            var spacerid = 'custombox-verticalspacer-' + fullid;
            var parts = fullid.split('-');
            var customid = parts[1];

            var spacerheight = $('#' + spacerid).css('height').replace(/em|px/, '');
            customboxverticalspacer.verticalspacerlocation[customid] = parseInt(spacerheight);
            customboxverticalspacer.verticalspacerpagelocation[customid] = e.pageY;
            customboxverticalspacer.catched = true;
            e.stopImmediatePropagation();
            return false;
        },

        mouseup: function(e) {

            var that = $(this);
            log.debug('Releasing ' + that.attr('id'));
            var fullid = that.attr('id').replace('custombox-verticalspacer-', '');
            var parts = fullid.split('-');
            var courseid = parts[0];
            var customid = parts[1];

            customboxverticalspacer.verticalspacerlocation[customid] = 0;
            customboxverticalspacer.verticalspacerpagelocation[customid] = 0;
            customboxverticalspacer.catched = false;

            // Send actualized value to server.
            var height = that.css('height');
            var url = cfg.wwwroot + '/mod/customlabel/type/verticalspacer/ajax/service.php';
            url += '?id=' + courseid;
            url += '&cid=' + customid;
            url += '&height=' + height.replace(/em|px/, '');

            $.get(url);
            e.stopImmediatePropagation();
            return false;
        },

        mousemove: function(e) {

            if (!customboxverticalspacer.catched) {
                return;
            }

            var that = $(this);
            log.debug('Moving ' + that.attr('id'));
            var fullid = that.attr('id').replace('verticalspacer-handle-', '');
            var parts = fullid.split('-');
            var spacerid = 'custombox-verticalspacer-' + fullid;
            var customid = parts[1];
            var dist, height, newheight;

            if (customboxverticalspacer.verticalspacerlocation[customid] != 0) {
                dist = e.pageY - customboxverticalspacer.verticalspacerpagelocation[customid];
                height = customboxverticalspacer.verticalspacerlocation[customid];
                newheight = (height + dist) + 'px';
                $('#' + spacerid).css('height', newheight);
            }
            e.stopImmediatePropagation();
            return false;
        }
    };

    return customboxverticalspacer;
});
