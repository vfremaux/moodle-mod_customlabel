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

require('../../config.php');

$type = required_param('type', PARAM_TEXT);
$shape = required_param('shape', PARAM_TEXT);
$stroke = optional_param('stroke', 'gray', PARAM_TEXT);

if (file_exists($CFG->dirroot.'/mod/customlabel/pix/'.$shape.'.svg')) {
    header('Content-type: image/svg+xml');

    $icon = implode("\n", file($CFG->dirroot.'/mod/customlabel/pix/'.$shape.'.svg'));

    if (strpos('#', $stroke) === 0) {
        // Starts with a #.
        // Convert to rgb.
        $stroke = hex_to_rgb($stroke);
    }

    $icon = str_replace('{{customlabeltype}}', $type, $icon);
    $icon = str_replace('{{stroke}}', $stroke, $icon);

    echo $icon;
}

function hex_to_rgb($hex, $alpha = false) {
    $hex = str_replace('#', '', $hex);
    $length = strlen($hex);
    $r = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
    $g = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
    $b = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
    if ( $alpha ) {
        return 'rgba('.$r.','.$g.','.$b.','.$alpha.')';
    }
    return 'rgb('.$r.','.$g.','.$b.')';
}