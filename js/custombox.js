/*
 *
 */
// jshint unused:false, undef:false

function togglecustom(id, wwwroot) {
    var elm = document.getElementById('custom'+id);
    if (elm) {
        var elmthumb = document.getElementById('custom-thumb'+id);
        var elmctl = document.getElementById('customctl'+id);
        if (elm.style.display == 'block'){
             elm.style.display = 'none';
             if (elmthumb) elmthumb.style.display = 'none';
             elmctl.src = wwwroot+'/mod/customlabel/pix/plus.gif';
        } else {
             elm.style.display = 'block';
             if (elmthumb) elmthumb.style.display = 'block';
             elmctl.src = wwwroot+'/mod/customlabel/pix/minus.gif';
        }
    }
}

function setupcustom(id, state, wwwroot) {
    var elm = document.getElementById('custom'+id);
    if (elm) {
        var elmthumb = document.getElementById('custom-thumb'+id);
        var elmctl = document.getElementById('customctl'+id);
        if (state == 1){
             elm.style.display = 'block';
             if (elmthumb) elmthumb.style.display = 'block';
             elmctl.src = wwwroot+'/mod/customlabel/pix/minus.gif';
        } else {
             elm.style.display = 'none';
             if (elmthumb) elmthumb.style.display = 'none';
             elmctl.src = wwwroot+'/mod/customlabel/pix/plus.gif';
        }
    }
}

function togglecustomstring(id, label1, label2) {
    if ($('#custom'+id).css('display') == 'block'){
        $('#custom'+id).css('display', 'none');
        $('#customctl'+id).html(label1);
    } else {
        $('#custom'+id).css('display', 'block');
        $('#customctl'+id).html(label2);
    }
}

function setupcustomstring(id, state, label1, label2) {
    if (state == 1){
        $('#custom'+id).css('display', 'block');
        $('#customctl'+id).html(label2);
    } else {
        $('#custom'+id).css('display', 'none');
        $('#customctl'+id).html(label1);
    }
}