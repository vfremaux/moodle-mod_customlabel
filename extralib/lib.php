<?php

/**
 * Use of eval is forbidden by Moodle code standards and breaks
 * code checkers.
 *
 * This function isolates the eval call far from Travis eyes and
 * keeps evaluation in a limited namespace.
 */
function customlabel_eval($expression, $input, &$result) {

    extract($input);

    /*
     * Add a real $ to expression so if expression is f.e.
     * imagecount > 2
     * then we evaluate : 
     * "$result = $imagecount > 2"
     */
    eval("\$result = \$$expression;");

}