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
 * @package     mod_customlabel
 * @category    mod
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   2016 Valery Fremaux (http://www.mylearningfactory.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/lib/externallib.php');
require_once($CFG->dirroot.'/mod/customlabel/locallib.php');

class mod_customlabel_external extends external_api {

    // Get content.

    /**
     * checks some common parameters
     *
     * @return external_function_parameters
     */
    public static function validate_element_parameters($parameters, $input) {
        global $DB;

        $params = self::validate_parameters($parameters, $input);

        switch ($input['cidsource']) {
            case 'idnumber':
                if (!$cid = $DB->get_field('course_modules', 'instance', array('idnumber' => $input['cid']))) {
                    throw new moodle_exception('Course module not found');
                }
                break;
            case 'id':
                $cid = $input['cid'];
                break;
            default:
                throw new moodle_exception('Invalid id source for element');
        }

        if (!$DB->record_exists('customlabel', array('id' => $cid))) {
            throw new moodle_exception('Course element instance not found');
        }

        return $params;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function get_content_parameters() {
        global $CFG;

        $desc = 'source for the course element id, can be either \'id\', or \'idnumber\'';
        return new external_function_parameters(
            array(
                'cidsource' => new external_value(PARAM_ALPHA, $desc),
                'cid' => new external_value(PARAM_TEXT, 'Element id'),
                'lang' => new external_value(PARAM_TEXT, 'The output language', VALUE_DEFAULT, $CFG->lang),
            )
        );
    }

    /**
     * Get a customlabel internal content
     *
     * @param string $cidsource the source field for the course identifier.
     * @param string $cid the courseid id. If 0, will get all the certificates of the site
     *
     * @return external_description
     */
    public static function get_content($cidsource, $cid, $lang) {
        global $DB;

        $parameters = array(
            'cidsource' => $cidsource,
            'cid' => $cid,
            'lang' => $lang
        );
        $validparams = self::validate_element_parameters(self::get_content_parameters(), $parameters);

        // Do what needs to be done.
        switch ($parameters['cidsource']) {
            case 'idnumber':
                $cm = $DB->get_record('course_modules', array('idnumber' => $cid));
                $instanceid = $cm->instance;
                break;

            case 'id':
                $instanceid = $cid;
                break;
        }

        $instance = $DB->get_record('customlabel', array('id' => $instanceid));

        // Need filter string.
        return $instance->processedcontent;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function get_content_returns() {
        return new external_value(PARAM_RAW, 'The course element content');
    }

    // Get attribute.

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function get_attribute_parameters() {
        $desc = 'source for the course element id, can be either \'id\', or \'idnumber\'';
        return new external_function_parameters(
            array(
                'cidsource' => new external_value(PARAM_ALPHA, $desc),
                'cid' => new external_value(PARAM_TEXT, 'Element id'),
                'attributekey' => new external_value(PARAM_TEXT, 'Attribute key id'),
            )
        );
    }

    /**
     * Get one or all attributes from a course
     *
     * @param string $cidsource the field for the course element identifier. 'idnumber'
     * addresses the course module idnumber, while id
     * addresses the element instance record.
     * @param string $cid the courseid id. If 0, will get all the certificates of the site
     * @param string $attributekey the attribute key. If 0, will get all the attributes of the element
     *
     * @return external_description
     */
    public static function get_attribute($cidsource, $cid, $attributekey) {
        global $DB;

        $parameters = array(
            'cidsource' => $cidsource,
            'cid' => $cid,
            'attributekey' => $attributekey
        );
        $validparams = self::validate_element_parameters(self::get_attribute_parameters(), $parameters);

        // Do what needs to be done.
        switch ($parameters['cidsource']) {
            case 'idnumber':
                $cm = $DB->get_record('course_modules', array('idnumber' => $cid));
                $instanceid = $cm->instance;
                break;

            case 'id':
                $instanceid = $cid;
                break;
        }

        $instance = $DB->get_record('customlabel', array('id' => $instanceid));
        $cm = get_coursemodule_from_instance('customlabel', $instanceid);
        $instance->coursemodule = $cm->id;

        if ($attributekey == 'labelclass') {
            return $instance->labelclass;
        }

        $instanceobj = customlabel_load_class($instance, true);

        return $instanceobj->get_data($attributekey);
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function get_attribute_returns() {
        return new external_value(PARAM_RAW, 'The course element attribute value');
    }

    // Set attribute.

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function set_attribute_parameters() {
        $desc = 'source for the course element id, can be either \'id\', or \'idnumber\'';
        return new external_function_parameters(
            array(
                'cidsource' => new external_value(PARAM_ALPHA, $desc),
                'cid' => new external_value(PARAM_TEXT, 'Element id'),
                'attributekey' => new external_value(PARAM_TEXT, 'Attribute id id'),
                'value' => new external_value(PARAM_RAW, 'Attribute value'),
            )
        );
    }

    /**
     * Sets a particular attribute value in a course element. Will not
     * refresh the content cache. You will have to call refresh for commiting.
     *
     * @param string $cidsource the source field for the course element identifier.
     * @param string $cid the course element id
     * @param string $attributeid the attribute id. If 0, will get all the attributes of the element
     * @param string $value the new value
     *
     * @return external_description
     */
    public static function set_attribute($cidsource, $cid, $attributekey, $value) {
        global $DB;

        $parameters = array(
            'cidsource' => $cidsource,
            'cid' => $cid,
            'attributekey' => $attributekey,
            'value' => $value
        );
        $validparams = self::validate_element_parameters(self::set_attribute_parameters(), $parameters);

        // Do what needs to be done.
        switch ($parameters['cidsource']) {
            case 'idnumber':
                $cm = $DB->get_record('course_modules', array('idnumber' => $cid));
                $instanceid = $cm->instance;
                break;

            case 'id':
                $instanceid = $cid;
                break;
        }

        $instance = $DB->get_record('customlabel', array('id' => $instanceid));
        $cm = get_coursemodule_from_instance('customlabel', $instanceid);
        $instance->coursemodule = $cm->id;
        $instanceobj = customlabel_load_class($instance, true);
        debug_trace("Updating customlabel $attributekey with $value ");
        $instanceobj->update_data($attributekey, $value);

        return true;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function set_attribute_returns() {
        return new external_value(PARAM_BOOL, 'The success status');
    }

    // Refresh.

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function refresh_parameters() {
        $desc = 'source for the course element id, can be either \'id\', or \'idnumber\'';
        return new external_function_parameters(
            array(
                'cidsource' => new external_value(PARAM_ALPHA, $desc),
                'cid' => new external_value(PARAM_TEXT, 'Element id'),
            )
        );
    }

    /**
     * Refreshes the content cache.
     *
     * @param string $cidsource the source field for the course element identifier.
     * @param string $cid the course element id
     *
     * @return external_description
     */
    public static function refresh($cidsource, $cid) {
        global $DB;

        $parameters = array(
            'cidsource'  => $cidsource,
            'cid'  => $cid
        );
        // Do not call elements as there are more id sources.
        $validparams = self::validate_parameters(self::refresh_parameters(), $parameters);

        // Do what needs to be done.
        $instanceids = array();
        switch ($parameters['cidsource']) {
            case 'idnumber':
                if (!$cm = $DB->get_record('course_modules', array('idnumber' => $cid))) {
                    throw new moodle_exception('Bad course module');
                }
                $instanceid = $cm->instance;
                break;

            case 'courseidnumber':
                if (!$course = $DB->get_record('course', array('idnumber' => $cid))) {
                    throw new moodle_exception('Bad course');
                }
                customlabel_course_regenerate($course, 'all');
                return true;

            case 'courseid':
                if (!$course = $DB->get_record('course', array('id' => $cid))) {
                    throw new moodle_exception('Bad course');
                }
                if ($result = $DB->get_records('customlabel', array('course' => $course->id))) {
                    $instanceids = array_keys($result);
                }
                customlabel_course_regenerate($course, 'all');
                return true;

            case 'courseshortname':
                if (!$course = $DB->get_record('course', array('shortname' => $cid))) {
                    throw new moodle_exception('Bad course');
                }
                customlabel_course_regenerate($course, 'all');
                return true;

            case 'id':
                $instanceid = $cid;
                break;
        }

        $instance = $DB->get_record('customlabel', array('id' => $instanceid));
        $course = $DB->get_record('course', array('id' => $instance->course));
        customlabel_regenerate($instance, $instance->labelclass, $course);

        return true;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function refresh_returns() {
        return new external_value(PARAM_BOOL, 'The success status');
    }

    // Get Metadata Domain.

    public static function validate_mtd_parameters($parameters, $input) {
        global $DB;

        $config = get_config('customlabel');

        $result = self::validate_parameters($parameters, $input);

        switch ($result['domain']->type) {

            case 'id':
                if (!$DB->record_exists($config->classification_type_table, array('id' => $result['domain']->id))) {
                    throw new moodle_exception('Domain type missing by id');
                }
                $result['domainid'] = $result['domain']->id;
                break;

            case 'code': {
                $params = array('code' => $result['domain']->id);
                if (!$type = $DB->record_exists($config->classification_type_table, $params, 'id,id')) {
                    throw new moodle_exception('Domain type missing by code '.$result['domain']->id);
                }
                $result['domainid'] = $type->id;
                break;
            }

            case 'name': {
                $params = array('name' => $result['domain']->id);
                if (!$type = $DB->record_exists($config->classification_type_table, $params, 'id,id')) {
                    throw new moodle_exception('Domain type missing by name '.$result['domain']->id);
                }
                $result['domainid'] = $type->id;
                break;
            }

            default;
                throw new moodle_exception('Bad domain type');

        }
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function get_mtd_domain_parameters() {
        return new external_function_parameters(
            array(
                'domain' => new external_single_structure(
                    array(
                        'type' => new external_value(PARAM_TEXT, 'Identifier field, can be "id", "name" or "code"'),
                        'id' => new external_value(PARAM_TEXT, 'Domain identifier'),
                    )
                )
            )
        );
    }

    /**
     * Get the complete set of values for a metadata domain.
     *
     * @param string $cidsource the source field for the course element identifier.
     * @param string $cid the course element id
     *
     * @return external_description
     */
    public static function get_mtd_domain($domain) {
        global $DB;

        $parameters = array(
            'domain' => $domain
        );
        $params = self::validate_mtd_parameters(self::get_mtd_domain_parameters(), $parameters);

        $config = get_config('customlabel');

        $type = $DB->get_record($config->classification_type_table, array('id' => $params['domainid']));

        $params = array($config->classification_value_type_key => $type->id);
        $domainvalues = $DB->get_records($config->classification_value_table, $params);

        return $domainvalues;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function get_mtd_domain_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'code' => new external_value(PARAM_TEXT, 'Domain value code'),
                    'value' => new external_value(PARAM_TEXT, 'Domain value label'),
                    'id' => new external_value(PARAM_INT, 'Internal ID for classification reference'),
                )
            )
        );
    }

}
