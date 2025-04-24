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
 * WS services
 *
 * @package     mod_customlabel
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   2016 Valery Fremaux (http://www.mylearningfactory.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/lib/externallib.php');
require_once($CFG->dirroot.'/mod/customlabel/lib.php');
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
                if (!$cid = $DB->get_field('course_modules', 'instance', ['idnumber' => $input['cid']])) {
                    throw new moodle_exception('Course module not found');
                }
                break;
            case 'id':
                $cid = $input['cid'];
                break;
            default:
                throw new moodle_exception('Invalid id source for element');
        }

        if (!$DB->record_exists('customlabel', ['id' => $cid])) {
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
            [
                'cidsource' => new external_value(PARAM_ALPHA, $desc),
                'cid' => new external_value(PARAM_TEXT, 'Element id'),
                'lang' => new external_value(PARAM_TEXT, 'The output language', VALUE_DEFAULT, $CFG->lang),
            ]
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
        global $CFG;

        if (customlabel_supports_feature('api/ws')) {
            include_once($CFG->dirroot.'/mod/customlabel/pro/externallib.php');
            return mod_customlabel_external_extended::get_content($cidsource, $cid, $lang);
        } else {
            throw new moodle_exception("Call to \"pro\" version WS additional implementation cannot be satisfied");
        }
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
            [
                'cidsource' => new external_value(PARAM_ALPHA, $desc),
                'cid' => new external_value(PARAM_TEXT, 'Element id'),
                'attributekey' => new external_value(PARAM_TEXT, 'Attribute key id'),
            ]
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
        global $CFG;

        if (customlabel_supports_feature('api/ws')) {
            include_once($CFG->dirroot.'/mod/customlabel/pro/externallib.php');
            return mod_customlabel_external_extended::get_attribute($cidsource, $cid, $attributekey);
        } else {
            throw new moodle_exception("Call to \"pro\" version WS additional implementation cannot be satisfied");
        }
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
            [
                'cidsource' => new external_value(PARAM_ALPHA, $desc),
                'cid' => new external_value(PARAM_TEXT, 'Element id'),
                'attributekey' => new external_value(PARAM_TEXT, 'Attribute id id'),
                'value' => new external_value(PARAM_RAW, 'Attribute value'),
            ]
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
        global $CFG;

        if (customlabel_supports_feature('api/ws')) {
            include_once($CFG->dirroot.'/mod/customlabel/pro/externallib.php');
            return mod_customlabel_external_extended::set_attribute($cidsource, $cid, $attributekey, $value);
        } else {
            throw new moodle_exception("Call to \"pro\" version WS additional implementation cannot be satisfied");
        }
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
            [
                'cidsource' => new external_value(PARAM_ALPHA, $desc),
                'cid' => new external_value(PARAM_TEXT, 'Element id'),
            ]
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
        global $CFG;

        if (customlabel_supports_feature('api/ws')) {
            include_once($CFG->dirroot.'/mod/customlabel/pro/externallib.php');
            return mod_customlabel_external_extended::refresh($cidsource, $cid);
        } else {
            throw new moodle_exception("Call to \"pro\" version WS additional implementation cannot be satisfied");
        }
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
                if (!$DB->record_exists($config->classification_type_table, ['id' => $result['domain']->id])) {
                    throw new moodle_exception('Domain type missing by id');
                }
                $result['domainid'] = $result['domain']->id;
                break;

            case 'code': {
                $params = ['code' => $result['domain']->id];
                if (!$type = $DB->record_exists($config->classification_type_table, $params, 'id,id')) {
                    throw new moodle_exception('Domain type missing by code '.$result['domain']->id);
                }
                $result['domainid'] = $type->id;
                break;
            }

            case 'name': {
                $params = ['name' => $result['domain']->id];
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
            [
                'domain' => new external_single_structure(
                    [
                        'type' => new external_value(PARAM_TEXT, 'Identifier field, can be "id", "name" or "code"'),
                        'id' => new external_value(PARAM_TEXT, 'Domain identifier'),
                    ]
                ),
            ]
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
        global $CFG;

        if (customlabel_supports_feature('api/ws')) {
            include_once($CFG->dirroot.'/mod/customlabel/pro/externallib.php');
            return mod_customlabel_external_extended::get_mtd_domain($domain);
        } else {
            throw new moodle_exception("Call to \"pro\" version WS additional implementation cannot be satisfied");
        }
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function get_mtd_domain_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                [
                    'code' => new external_value(PARAM_TEXT, 'Domain value code'),
                    'value' => new external_value(PARAM_TEXT, 'Domain value label'),
                    'id' => new external_value(PARAM_INT, 'Internal ID for classification reference'),
                ]
            )
        );
    }

    public static function add_instance_parameters() {
        $desc = 'Source for the course id, can be either \'id\', or \'idnumber\', or \'shortname\'';
        return new external_function_parameters(
            [
                'cidsource' => new external_value(PARAM_ALPHA, $desc),
                'cid' => new external_value(PARAM_TEXT, 'Course identifier'),
                'sectionnum' => new external_value(PARAM_NUMERIC, 'Section/page num'),
                'position' => new external_value(PARAM_NUMERIC, 'Position in section/page sequence, 0 (end) or -1 (start)'),
                'labeltype' => new external_value(PARAM_NUMERIC, 'Label type name'),
                'idnumber' => new external_value(PARAM_NUMERIC, 'Idnumber value for associated course module'),
            ]
        );
    }

    public static function add_instance($cidsource, $cid, $sectionnum, $position, $labeltype, $idnumber) {
        global $CFG;

        if (customlabel_supports_feature('api/ws')) {
            include_once($CFG->dirroot.'/mod/customlabel/pro/externallib.php');
            return mod_customlabel_external_extended::add_instance($cidsource, $cid, $sectionnum, $position, $labeltype, $idnumber);
        } else {
            throw new moodle_exception("Call to \"pro\" version WS additional implementation cannot be satisfied");
        }
    }

    public static function add_instance_returns() {
        return new external_single_structure(
            [
                'id' => new external_value(PARAM_INT, 'Course module id'),
                'idnumber' => new external_value(PARAM_TEXT, 'Course module idnumber'),
                'attributes' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'name' => new external_value(PARAM_TEXT, 'Label attribute name'),
                            'type' => new external_value(PARAM_TEXT, 'Label attribute type'),
                        ]
                    )
                ),
            ]
        );
    }

}
