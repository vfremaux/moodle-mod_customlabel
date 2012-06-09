
/*
* launch a call for targets renegociation
*
*/

function urlencode(str) {
	return escape(str).replace(/\+/g,'%2B').replace(/%20/g, '+').replace(/\*/g, '%2A').replace(/\//g, '%2F').replace(/@/g, '%40');
}

function applyconstraints(wwwroot, typestr, selector, targets){

	if (targets == '') return;	
	targetsarr = targets.split(',');
	
	var selectedopts = new Array();

	// get constraints in activated select	
	i = 0;
	for (j = 0 ; j < selector.options.length ; j++) {
		if (selector.options[j].selected){
			selectedopts[i] = selector.options[j].value;
			i++;
		}
	}
	optionstring = selectedopts.join(',');

	// get selection constraints in targets select	
	selectedtargetopts = [];
	i = 0;
	for (target in targetsarr){
		targetname = targetsarr[target];
		targetsel = document.getElementById('menu'+targetname);
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

	YAHOO.lang.JSON.useNativeStringify = true;
	formvaluestring = urlencode(YAHOO.lang.JSON.stringify(selectedtargetopts));
	
	for(target in targetsarr){
		targetseldiv = document.getElementById('div_menu_'+targetsarr[target]);
		targetseldiv.innerHTML = '<img src="'+wwwroot+'/theme/pairformance/pix/ajax-loader.gif" hspace="10" vspace="10" />';		
	}
	
	params = "selector="+selector.name+"&targets="+targets+"&type="+typestr+"&constraints="+optionstring+'&selection='+formvaluestring;
    var url = wwwroot+"/mod/customlabel/ajax/applyconstraints.php?"+params;

	var responseSuccess = function(o){
		var selectors = YAHOO.lang.JSON.parse(o.responseText);

		targetsarr = targets.split(',');

		// dispatch in selectors
		for (target in targetsarr){
			if (selectors[targetsarr[target]]){
				targetseldiv = document.getElementById('div_menu_'+targetsarr[target]);
				targetseldiv.innerHTML = selectors[targetsarr[target]];
				/*
				// empty option table
				for(o = 0 ; o < targetsel.options.length ; o++){
					targetsel.removeChild(targetsel.options[o]);
				}
				targetsel.options.length = 0;
				// refill option table
				// for(o = 0 ; o < selectors[targetsarr[target]].length ; o++){
				targetobj = selectors[targetsarr[target]];
				for(key in targetobj){
					// alert(key);
					targetsel.options[o] = new Option(selectors[targetsarr[target]][key], key, false, false);
					o++;
				}
				*/
			}
		}
	};

    var responseFailure = function(o){
     	alert("responseFailure " + o);
    };

    var AjaxObject = {
    	handleSuccess:function(o){
            this.processResult(o);
        },
    
        handleFailure:function(o){
            alert('Ajax failure');
        },
    
        processResult:function(o){
        },
    
    	startRequest:function(){    	    
    		YAHOO.util.Connect.asyncRequest('GET', url, callback, params);
    	}
    };
    
    var callback = {
        success:responseSuccess,
        failure:responseFailure,
        scope: AjaxObject
    };
    
     AjaxObject.startRequest();   	
}