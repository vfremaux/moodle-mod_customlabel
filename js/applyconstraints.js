/**
 * launch a call for targets renegociation
 *
 */
// jshint unused:false, undef:false

function urlencode(str) {
    str = escape(str).replace('/\+/g','%2B').replace('/%20/g', '+').replace('/\*/g', '%2A');
    str = str.replace('/\//g', '%2F').replace('/@/g', '%40');
    return str;
}

function applyconstraints(wwwroot, typestr, selector, targets) {

    var i,j;

    if (targets === '') {
        return;
    }
    targetsarr = targets.split(',');

    var selectedopts = [];

    // Get constraints in activated select.
    i = 0;
    for (j = 0; j < selector.options.length; j++) {
        if (selector.options[j].selected) {
            selectedopts[i] = selector.options[j].value;
            i++;
        }
    }
    optionstring = selectedopts.join(',');

    // Get selection constraints in targets select.
    selectedtargetopts = [];
    i = 0;
    for (var target in targetsarr) {
        targetname = targetsarr[target];
        targetsel = document.getElementById('id_' + targetname);
        selectedtargetopts[i] = targetname;
        i++;
        selectedtargetopts[i] = [];
        k = 0;
        for (j = 0; j < targetsel.options.length; j++) {
            if (targetsel.options[j].selected) {
                selectedtargetopts[i][k] = targetsel.options[j].value;
                k++;
            }
        }
        i++;
    }

    formvaluestring = urlencode(JSON.stringify(selectedtargetopts));

    params = "selector=" + selector.name + "&targets=" + targets + "&type=" + typestr + "&constraints=" + optionstring;
    params += '&selection=' + formvaluestring;
    var url = wwwroot + "/mod/customlabel/ajax/applyconstraints.php?" + params;

    $.get(url, '', function(data, status) {
        var selectors = JSON.parse(data);

        targetsarr = targets.split(',');

        // Dispatch in selectors.
        for (var target in targetsarr) {
            if (selectors[targetsarr[target]]) {
                str = '<input type="hidden" name="' + targetsarr[target] + '" value="_qf__force_multiselect_submission">';
                str += selectors[targetsarr[target]];
                $('#fitem_id_' + targetsarr[target] + ' .felement.fselect').html(str);
            }
        }
    });
}

function applyconstraintsmenu(wwwroot, typestr, selector, targets) {

    var i,j;

    if (targets === '') {
        return;
    }
    targetsarr = targets.split(',');

    var selectedopts = [];

    // Get constraints in activated select.
    i = 0;
    for (j = 0; j < selector.options.length; j++) {
        if (selector.options[j].selected) {
            selectedopts[i] = selector.options[j].value;
            i++;
        }
    }
    optionstring = selectedopts.join(',');

    // Get selection constraints in targets select.
    selectedtargetopts = [];
    i = 0;
    for (var target in targetsarr) {
        targetname = targetsarr[target];
        targetsel = document.getElementById('menu' + targetname);
        selectedtargetopts[i] = targetname;
        i++;
        selectedtargetopts[i] = [];
        k = 0;
        for (j = 0; j < targetsel.options.length; j++) {
            if (targetsel.options[j].selected){
                selectedtargetopts[i][k] = targetsel.options[j].value;
                k++;
            }
        }
        i++;
    }

    formvaluestring = urlencode(JSON.stringify(selectedtargetopts));

    params = "selector=" + selector.name + "&targets=" + targets + "&type=" + typestr + "&constraints=" + optionstring;
    params += '&selection=' + formvaluestring + '&variant=menu';
    var url = wwwroot + "/mod/customlabel/ajax/applyconstraints.php?" + params;

    $.get(url, '', function(data, status){
        var selectors = JSON.parse(data);

        targetsarr = targets.split(',');

        // Dispatch in selectors.
        for (target in targetsarr) {
            if (selectors[targetsarr[target]]) {
                str = '<input type="hidden" name="' + targetsarr[target] + '" value="_qf__force_multiselect_submission">';
                str += selectors[targetsarr[target]];
                $('#div_menu_' + targetsarr[target]).html(str);
            }
        }
    });
}
