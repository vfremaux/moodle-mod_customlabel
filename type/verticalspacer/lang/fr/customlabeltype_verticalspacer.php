<?php

/**
* This is a local class contextual translation file for field names and 
* list options.
* this file is automatically loaded by the 
* /mod/customlabel/lang/xx_utf8/customlabel.php
* module language file.
*
*/

$string['verticalspacer:view'] = 'Peut voir le contenu';
$string['verticalspacer:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Espaceur vertical';
$string['typename'] = 'Espacement vertical';
$string['configtypename'] = 'Permet d\'insérer et régler des espacements vides verticaux dans les colonnes.';
$string['unit'] = 'Unité';
$string['spacing'] = 'Espacement';
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