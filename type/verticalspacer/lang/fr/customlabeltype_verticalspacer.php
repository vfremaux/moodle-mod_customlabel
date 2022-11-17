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
defined('MOODLE_INTERNAL') || die();

$string['verticalspacer:view'] = 'Peut voir le contenu';
$string['verticalspacer:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours&nbsp;: Espaceur vertical';
$string['typename'] = 'Espacement vertical';
$string['configtypename'] = 'Permet d\'insérer et régler des espacements vides verticaux dans les colonnes.';
$string['unit'] = 'Unité&nbsp;';
$string['spacing'] = 'Espacement&nbsp;';
$string['px'] = 'Pixels';
$string['em'] = 'Hauteur de police';

$string['family'] = 'special';

$string['template'] = '
<div id="custombox-verticalspacer<%%customid%%>" class="custombox-verticalspacer" style="height:<%%spacing%%>px;min-height:<%%spacing%%>px">
&nbsp;
<%if %%editing%% %>
<img id="verticalspacer<%%customid%%>" src="<%%dragimageurl%%>">
<script type="text/javascript">
var verticalspacerlocation<%%customid%%> = 0;
var verticalspacerpagelocation<%%customid%%> = 0;

// Remove all events
$(\'#verticalspacer<%%customid%%>\').off();

$(\'#verticalspacer<%%customid%%>\').mousedown(function(event) {
    verticalspacerlocation<%%customid%%> = parseInt($(\'#custombox-verticalspacer<%%customid%%>\').css(\'height\').replace(/em|px/, \'\'));
    verticalspacerpagelocation<%%customid%%> = event.pageY;
    event.stopImmediatePropagation();
    return false;
});
$(\'#verticalspacer<%%customid%%>\').mouseup(function(event) {
    verticalspacerlocation<%%customid%%> = 0;
    verticalspacerpagelocation<%%customid%%> = 0;

    height = $(\'#custombox-verticalspacer<%%customid%%>\').css(\'height\');
    url = \'<%%wwwroot%%>/mod/customlabel/type/verticalspacer/ajax/service.php?id=<%%courseid%%>&cid=<%%cid%%>&height=\'+height.replace(/em-px/, \'\');
    $.get(url);
    event.stopImmediatePropagation();
    return false;
});
$(\'#verticalspacer<%%customid%%>\').mousemove(function(event) {
    if (verticalspacerlocation<%%customid%%> != 0) {
        dist = event.pageY - verticalspacerpagelocation<%%customid%%>;
        height = verticalspacerlocation<%%customid%%>;
        newheight = (height + dist) + \'px\';
        $(\'#custombox-verticalspacer<%%customid%%>\').css(\'height\', newheight);
    }
    event.stopImmediatePropagation();
    return false;
});
</script>
<%endif %>
</div>';