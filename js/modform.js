/*
 *
 */
// jshint undef:true unset:true

function type_change_submit(advicetext, courseid, section, returntomod, sesskey, updatelabelid) {
    if (confirm(advicetext)) {
        typeselobj = document.getElementById('id_menulabelclass');
        // Odd difference between two minor releases.
        if (!typeselobj) {
            typeselobj = document.getElementById('menulabelclass');
        }
        if (updatelabelid == 0) {
            url = './mod.php?id=' + courseid + '&section=' + section + '&sesskey=' + sesskey;
            url += '&add=customlabel&returntomod=' + returntomod + '&type=' + typeselobj.options[typeselobj.selectedIndex].value;
        } else {
            url = M.cfg.wwwroot + '/mod/customlabel/mod.php?update=' + updatelabelid + '&sesskey=' + sesskey;
            url += '&sr=' + section + '&returntomod=' + returntomod + '&type=' + typeselobj.options[typeselobj.selectedIndex].value;
        }
        document.location.href = url;
    }
}
