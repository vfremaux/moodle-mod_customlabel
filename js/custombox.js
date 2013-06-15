function togglecustom(id, wwwroot){
	var elm = document.getElementById('custom'+id);
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

function setupcustom(id, state, wwwroot){
	var elm = document.getElementById('custom'+id);
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