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
 *
 * @package    customlabeltype_localdokuwikicontent
 *
 * @author     Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Locally gets content from a dokuwiki installed on the same server.
 * @param string $contentpage the dokuwiki page id.
 * @param string $lang the required language if multiple language available.
 * @return string the content filtered out of <!-- nomoodle --><!-- /nomoodle --> comments.
 * local wiki links are targetted as externals from within moodle.
 */
function customlabeltype_localwikicontent_get_page_content($contentpage, $lang) {
    global $OUTPUT, $CFG;

    $config = get_config('customlabeltype_localdokuwikicontent');

    $basedir = str_replace('{lang}', $lang, $config->basedir);

    if (!is_dir($basedir)) {
        if ($CFG->debug == DEBUG_DEVELOPER) {
            return $OUTPUT->notification(get_string('errornowiki', 'customlabeltype_localdokuwikicontent', $basedir), 'error');
        }
        return $OUTPUT->notification(get_string('errornowiki', 'customlabeltype_localdokuwikicontent', ''), 'error');
    }

    $output = [];

    $path = core_text::strtolower(str_replace(':', '/', $contentpage));
    $contentfile = $basedir.'/data/pages/'.$path.'.txt';
    $contentfile = str_replace('//', '/', $contentfile);

    if (!file_exists($contentfile)) {
        if ($CFG->debug == DEBUG_DEVELOPER) {
            return $OUTPUT->notification(get_string('errormissingpage', 'customlabeltype_localdokuwikicontent', $contentfile), 'error');
        }
        return $OUTPUT->notification(get_string('errormissingpage', 'customlabeltype_localdokuwikicontent', ''), 'error');
    }

    // Dokuwiki page cli generator needs 5.6.
    $cmd = '/usr/bin/php5.6 '.escapeshellarg($basedir.'/bin/render.php').' < '.escapeshellarg($contentfile);
    $result = exec($cmd, $output, $returnvar);

    $content = '';

    $content .= implode("\n", $output);

    // Process image links.
    $webroot = $config->webroot;
    $webroot = str_replace('{lang}', $lang, $webroot);
    /*
    $content = preg_replace('#(<img\s+?src=")'.$basedir.'/bin#','\\1'.$webroot.'/', $content);
    */

    // Remove moodle hidden by <!--nomoodle --><!--/nomoodle --> marking.
    /*
     * This will keep and empty paragraph.
     * for this marking to work, put key 'htmlok' to 1 in /conf/dokuwiki.php
     * Mark the text with <html><!-- nomoodle --></html> and <html><!-- /nomoodle --></html>
     */
    $content = preg_replace('/<!-- nomoodle -->(.*?)<!-- \/nomoodle -->/is', '', $content);
    // In case of escaped content.
    $content = preg_replace('/\\&lt;!-- nomoodle --\\&gt;(.*?)\\&lt;!-- \/nomoodle --\\&gt;/is', '', $content);

    // Replace image and links insertion base url.
    $content = str_replace($basedir.'/bin', $webroot, $content);

    // Externalise Wiki links
    $content = str_replace('href="'.$webroot, ' target="_blank" href="'.$webroot, $content);

    return $content;
}
