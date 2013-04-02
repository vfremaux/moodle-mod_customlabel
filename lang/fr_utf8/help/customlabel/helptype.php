<?php

$class = optional_param('class', '', PARAM_TEXT);
$field = optional_param('field', '', PARAM_TEXT);

$lang = current_language();

if (!empty($class) && !empty($field)){
	if (file_exists($CFG->dirroot."/mod/customlabel/type/{$class}/{$lang}/help/{$field}")){
		include $CFG->dirroot."/mod/customlabel/type/{$class}/{$lang}/help/{$field}";
	} else {
		notify("subhelp : /mod/customlabel/type/{$class}/{$lang}/help/{$field} does not exist");
	}
}

?>