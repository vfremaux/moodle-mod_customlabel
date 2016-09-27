/*
 *
 */

function type_change_submit(advicetext, courseid, section, returntomod, sesskey, updatelabelid) {
    if (confirm(advicetext)) {
        typeselobj = document.getElementById('id_menulabelclass');
        // odd difference between two minor releases.
        if (!typeselobj) {
            typeselobj = document.getElementById('menulabelclass');
        }
        if (updatelabelid == 0) {
            url = './mod.php?id='+courseid+'&section='+section+'&sesskey='+sesskey+'&add=customlabel&returntomod='+returntomod+'&type='+ typeselobj.options[typeselobj.selectedIndex].value;
        } else {
            url = '../mod/customlabel/mod.php?update='+updatelabelid+'&sesskey='+sesskey+'&sr='+section+'&returntomod='+returntomod+'&type='+ typeselobj.options[typeselobj.selectedIndex].value;
        }
        document.location.href = url;
    }
}
