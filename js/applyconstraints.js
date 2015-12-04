
/*
* launch a call for targets renegociation
*
*/

function urlencode(str) {
    return escape(str).replace(/\+/g,'%2B').replace(/%20/g, '+').replace(/\*/g, '%2A').replace(/\//g, '%2F').replace(/@/g, '%40');
}

function applyconstraints(wwwroot, typestr, selector, targets) {

    if (targets == '') return;
    targetsarr = targets.split(',');

    var selectedopts = new Array();

    // get constraints in activated select
    i = 0;
    for (j = 0 ; j < selector.options.length ; j++) {
        if (selector.options[j].selected) {
            selectedopts[i] = selector.options[j].value;
            i++;
        }
    }
    optionstring = selectedopts.join(',');

    // get selection constraints in targets select
    selectedtargetopts = [];
    i = 0;
    for (target in targetsarr) {
        targetname = targetsarr[target];
        targetsel = document.getElementById('id_'+targetname);
        selectedtargetopts[i] = targetname;
        i++;
        selectedtargetopts[i] = [];
        k = 0;    
        for (j = 0 ; j < targetsel.options.length ; j++) {
            if (targetsel.options[j].selected){
                selectedtargetopts[i][k] = targetsel.options[j].value;
                k++;
            }
        }
        i++;
    }

    formvaluestring = urlencode(JSON.stringify(selectedtargetopts));

    params = "selector="+selector.name+"&targets="+targets+"&type="+typestr+"&constraints="+optionstring+'&selection='+formvaluestring;
    var url = wwwroot+"/mod/customlabel/ajax/applyconstraints.php?"+params;

    $.get(url, '', function(data, status){
        var selectors = JSON.parse(data);

        targetsarr = targets.split(',');

        // dispatch in selectors
        for (target in targetsarr) {
            if (selectors[targetsarr[target]]) {
                str = '<input type="hidden" name="'+targetsarr[target]+'" value="_qf__force_multiselect_submission">';
                str += selectors[targetsarr[target]];
                $('#fitem_id_'+targetsarr[target]+' .felement.fselect').html(str);
            }
        }
    });
}
