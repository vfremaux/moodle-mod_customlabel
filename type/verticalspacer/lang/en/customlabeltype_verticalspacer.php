<?php

/**
* This is a local class contextual translation file for field names and 
* list options.
* this file is automatically loaded by the 
* /mod/customlabel/lang/xx_utf8/customlabel.php
* module language file.
*
*/

$string['verticalspacer:view'] = 'Can view the content';
$string['verticalspacer:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Vertical Spacer';
$string['typename'] = 'Vertical empty spacer for columns';
$string['configtypename'] = 'Enable plugin blank vertical areas in page';
$string['unit'] = 'Unit';
$string['spacing'] = 'Spacing amount';
$string['px'] = 'Pixels';
$string['em'] = 'Text line height';

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
    url = \'<%%wwwroot%%>/mod/customlabel/type/verticalspacer/ajax/service.php?id=<%%courseid%%>&cid=<%%cid%%>&height=\'+height.replace(/em|px/, \'\');
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