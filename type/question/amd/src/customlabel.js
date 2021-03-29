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

    customboxquestion = {

        init: function() {
            $('.custombox-qcm-btn').bind('click', this.submitquestion);

            log.debug('AMD customlabel question initialized.');
        },

        submitquestion: function() {

            var that = $(this);

            var cmid = that.attr('name').replace('custombox-qcm-btn-', '');
            var answers = $('.custombox-qcm-input-' + cmid + ' :checked');

            var aid = 0;
            if (answers.length > 0) {
                var answer = answers.first();
                aid = answer.attr('value');
            }

            // Send data to submission.
            var url = cfg.wwwroot + '/mod/customlabel/type/question/ajax/service.php';
            url += '?what=submit';
            url += '&cmid=' + cmid;
            url += '&answernum=' + aid;

            $('.custombox-qcm-answer-' + cmid).removeClass('correct');
            $('.custombox-qcm-answer-' + cmid).removeClass('incorrect');

            $.get(url, function(data){
                // Reset all answer styles.
                if (data.status === 2) {
                    $('#custombox-qcm-answer-' + data.cmid + '-' + data.aid).addClass('correct');
                } else {
                    $('#custombox-qcm-answer-' + data.cmid + '-' + data.aid).addClass('incorrect');
                }

                // Lock question if max attempts reached or good answer is given.
                if (data.locked || (data.status === 2)) {
                    $('.custombox-qcm-input-' + data.cmid + ' input').prop('disabled', true);
                    $('#custom-' + data.cmid + 'submit input').prop('disabled', true);
                }
            }, 'json');
        }
    };

    return customboxquestion;

});