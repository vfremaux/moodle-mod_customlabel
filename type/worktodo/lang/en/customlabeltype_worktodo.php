<?php

$string['worktodo:view'] = 'Can view the content';
$string['worktodo:addinstance'] = 'Can add an indstance';

$string['pluginname'] = 'Course element : Work to Do';
$string['typename'] = 'Work to do';
$string['configtypename'] = 'Enable subtype Work to do';
$string['worktodo'] = 'Work to Do';
$string['worktypefield'] = 'Work type';
$string['workeffortfield'] = 'Work effort';
$string['workmodefield'] = 'Work mode';
$string['estimatedworktime'] = 'Estimated time';
$string['linktomodule'] = 'Related Activity Module';

$string['family'] = 'pedagogic';

// Qualifier values

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
    <td class="custombox-header-thumb worktodo" width="2%" rowspan="3">
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
        <img src="/mod/customlabel/type/worktodo/clock.jpg" /> <%%estimatedworktime%%>
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