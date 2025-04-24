<?php
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

/**
 * Lang file.
 *
 * @package    customlabeltype_worktodo
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
defined('MOODLE_INTERNAL') || die();

$string['worktodo:view'] = 'Can view the content';
$string['worktodo:addinstance'] = 'Can add an indstance';

$string['pluginname'] = 'Course element: Work to Do';
$string['typename'] = 'Work to do';
$string['configtypename'] = 'Enable subtype Work to do';
$string['nature'] = 'Nature';
$string['effort'] = 'Effort';
$string['mode'] = 'Modality';
$string['worktodo'] = 'Work to Do';
$string['worktypefield'] = 'Work type';
$string['workeffortfield'] = 'Work effort';
$string['workmodefield'] = 'Work mode';
$string['showworktypefield'] = 'Show work type';
$string['showworkeffortfield'] = 'Show work effort';
$string['showworkmodefield'] = 'Show work mode';
$string['estimatedworktime'] = 'Estimated time';
$string['linktomodule'] = 'Related Activity Module';
$string['unassigned'] = '--- unassigned ---';

$string['family'] = 'pedagogic';

// Qualifier values.

$string['NQ'] = 'Unqualified';

$string['WORKEFFORT'] = 'Difficulty';
$string['WORKEFFORT_desc'] = 'The work effort needed to solve the requirement, compared to a \'normal\' effort.';
$string['VERYEASY'] = 'Very easy';
$string['EASY'] = 'Easy';
$string['MEDIUM'] = 'Medium';
$string['HARD'] = 'Hard';
$string['VERYHARD'] = 'Very hard';

$string['WORKMODE'] = 'Working modality';
$string['WORKMODE_desc'] = 'The peer environment of the work, who is it performed with.';
$string['ALONEONLINE'] = 'Lonely on line';
$string['ALONEOFFLINE'] = 'Lonely off line';
$string['TEAMONLINE'] = 'On line, in a working team or group';
$string['TEAMOFFLINE'] = 'Off line, in a working team or group';
$string['COURSEONLINE'] = 'On line, with the whole classroom';
$string['COURSEOFFLINE'] = 'Off line, with the whole classroom';
$string['COACHSYNCHRONOUS'] = 'Synchronous work with a teaching peer';
$string['COACHASYNCHRONOUS'] = 'Asynchronous exchanges with a teaching peer';

$string['WORKTYPE'] = 'Work type';
$string['WORKTYPE_desc'] = 'The type of work being done.';
$string['TRAINING'] = 'Training a skill';
$string['WRITING'] = 'Memo or note writing';
$string['INFOQUEST'] = 'Seeking for information';
$string['EXERCISE'] = 'Exercising on a sample';
$string['PROJECT'] = 'Driving a project';
$string['EXPERIMENT'] = 'Experimenting and discovering';
$string['SYNTHESIS'] = 'Writing a synthesis';

$string['template'] = '
<table class="custombox-worktodo" cellspacing="0" width="100%">
<tr valign="top">
    <td class="custombox-header-thumb worktodo" style="background-image : url(<%%icon%%>);" width="2%" rowspan="3">
    </td>
    <td class="custombox-header-caption worktodo" width="98%" colspan="2">
        Work to do
    </td>
</tr>
<tr valign="top">
    <td class="custombox-worktype worktodo" colspan="2">
        <span class="custombox-param worktodo">Nature: </span> <span class="custombox-value worktodo"><%%worktypefield%%></span>
    </td>
    <td class="custombox-timeexpected worktodo" align="right" width="40">
        <img src="<%%clock%%>" /> <%%estimatedworktime%%>
    </td>
</tr>
<tr valign="top">
    <td class="custombox-worktype worktodo">
        <span class="custombox-param worktodo">Effort: </span> <span class="custombox-value worktodo"><%%workeffortfield%%></span>
    </td>
    <td class="custombox-workmode worktodo" align="right" colspan="3">
        <span class="custombox-param worktodo">Mode: </span> <span class="custombox-value worktodo"><%%workmodefield%%></span>
    </td>
</tr>
<tr>
    <td class="custombox-content worktodo" colspan="2">
        <%%worktodo%%>
    </td>
</tr>
</table>
';
