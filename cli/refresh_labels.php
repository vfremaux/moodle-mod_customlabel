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
 * CLI interface for capturing and converting all certificates to pdcertificate
 *
 * @package mod_customlabel
 * @copyright 2016 Valery Fremaux (valery.fremaux@gmail.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CLI_VMOODLE_PRECHECK;

define('CLI_SCRIPT', true);
define('CACHE_DISABLE_ALL', true);
$CLI_VMOODLE_PRECHECK = true; // Force first config to be minimal.

require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');

if (!isset($CFG->dirroot)) {
    die ('$CFG->dirroot must be explicitely defined in moodle config.php for this script to be used');
}

require_once($CFG->dirroot.'/lib/clilib.php'); // Cli only functions.

// CLI options.
list($options, $unrecognized) = cli_get_params(
    array(
        'help' => false,
        'host' => false,
        'courses' => false,
        'types' => false,
        'dryrun' => false,
        'verbose' => false,
    ),
    array(
        'h' => 'help',
        'H' => 'host',
        'C' => 'courses',
        'T' => 'types',
        'd' => 'dryrun',
        'v' => 'verbose',
    )
);

// Display help.
if (!empty($options['help'])) {

"Options:
\t-h, --help              Print out this help
\t-H,--host               The hostname when in VMoodle environment
\t-C,--courses            Courses to process
\t-T,--types              Label types (classes)
\t-d,--dryrun             Dry run, tells what will be done, but does nothing
\t-v,--verbose            Verbose mode

\$ sudo -u www-data /usr/bin/php mod/customlabel/cli/refresh_labels.php --types=important,courseheading --host=http://myvhost.mymoodle.org
\$ sudo -u www-data /usr/bin/php mod/customlabel/cli/refresh_labels.php --courses=2,3,4 --host=http://myvhost.mymoodle.org
";
    // Exit with error unless we're showing this because they asked for it.
    exit(empty($options['help']) ? 1 : 0);
}

// Now get cli options.

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error("Not recognized options ".$unrecognized);
}

if (!empty($options['host'])) {
    // Arms the vmoodle switching.
    echo('Arming for '.$options['host']."\n"); // mtrace not yet available.
    define('CLI_VMOODLE_OVERRIDE', $options['host']);
}

// Replay full config whenever. If vmoodle switch is armed, will switch now config.
if (!$CLI_VMOODLE_PRECHECK) {
    require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php'); // Global moodle config file.
}
echo('Config check : playing for '.$CFG->wwwroot."\n");

require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

$courseids = explode(',', $options['courses']);
$labelclasses = explode(',', $options['types']);;

if (empty($courseids)) {
    $courses = array();
} else {
    $courses = $DB->get_records_list('course', 'id', $courseids);
}

if (empty($labelclasses)) {
    $labelclasses = 'all';
}

foreach ($courses as $courseid => $course) {
    mtrace("processing course $courseid : $course->shortname");
    customlabel_course_regenerate($course, $labelclasses, $options);
}

echo "Done.\n";
