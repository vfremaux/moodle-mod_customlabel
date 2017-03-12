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
 * This is a local class contextual translation file for field names and list options.
 * this file is automatically loaded by the /mod/customlabel/lang/xx_utf8/customlabel.php
 * module language file.
 *
 * @package    mod_customlabel
 * @category   mod
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  (C) 2008 onwards Valery Fremaux (http://www.mylearningfactory.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

$string['commentbox:view'] = 'Can view the content';
$string['commentbox:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Comment';
$string['typename'] = 'Comment boxes';
$string['configtypename'] = 'Enable subtype Comment boxes';
$string['comment'] = 'Comment ';
$string['readmorecontent'] = 'Read more content';
$string['initiallyvisible'] = 'Initially visible';

$string['family'] = 'generic';

$string['template'] = '<div class="custombox-commentbox">
<%%comment%%>
</div>
<%if %%readmorecontent%% %>
<div class="custombox-commentbox readmorelink">
    <a href="javascript:togglecustomstring(\'<%%customid%%>\', \'Read more...\', \'Read less...\')"><span id="customctl<%%customid%%>"><%%initialstring%%></span></a>
</div>
<div class="custombox-commentbox readmore" id="custom<%%customid%%>">
<%%readmorecontent%%>
</div>
<%endif %>
<script type="text/javascript">
setupcustomstring(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'Read more...\', \'Read less...\');
</script>
';
