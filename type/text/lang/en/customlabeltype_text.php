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
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['text:view'] = 'Can view the content';
$string['text:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element: Simple Text';
$string['typename'] = 'Simple Text';
$string['configtypename'] = 'Enable subtype Simple Text';
$string['textcontent'] = 'Content';
$string['readmorecontent'] = 'Read more content';
$string['initiallyvisible'] = 'Initally visible';
$string['readmore'] = 'Read more...';
$string['readless'] = 'Read less...';

$string['family'] = 'generic';

$string['template'] = '
<!-- standard default template for unclassed label. Don\'t change -->
<div class="custombox-text">
<%%textcontent%%>
</div>
<%if %%readmorecontent%% %>
<div class="custombox-text readmorelink">
    <a href="javascript:togglecustomstring(\'<%%customid%%>\', \'Read more...\', \'Read less...\')" ><span id="customctl<%%customid%%>"><%%initialstring%%></span></a>
</div>
<div class="custombox-text readmore" id="custom<%%customid%%>">
<%%readmorecontent%%>
</div>
<%endif %>
<script type="text/javascript">
setupcustomstring(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'Read more...\', \'Read less...\');
</script>
';