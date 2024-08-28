function ShowHide(id1, id2) {
  if (id1 != '') expMenu(id1);
  if (id2 != '') expMenu(id2);
  }

function expMenu(id) {
  var itm = null;
  if (document.getElementById) {
    itm = document.getElementById(id);
  } else if (document.all){
    itm = document.all[id];
  } else if (document.layers){
    itm = document.layers[id];
  }

  if (!itm) {
   // do nothing
  }
  else if (itm.style) {
    if (itm.style.display == "none") { itm.style.display = ""; }
    else { itm.style.display = "none"; }
  }
  else { itm.visibility = "show"; }
}

function Hide(id) {
  if (id != ''){

  var itm = null;
  if (document.getElementById) {
    itm = document.getElementById(id);
  } else if (document.all){
    itm = document.all[id];
  } else if (document.layers){
    itm = document.layers[id];
  }
  
  if (id == 'planoAcao')
  {
	 // document.getElementById('planoAcaoIframe').style.display = 'none';
	//  document.getElementById('planoAcaoIframe').visibility = '';
  }

  if (!itm) {
   // do nothing
  }
  else if (itm.style) {
     itm.style.display = "none";
  }
}
}


function Show(id) {
  if (id != ''){

  var itm = null;
  if (document.getElementById) {
    itm = document.getElementById(id);
  } else if (document.all){
    itm = document.all[id];
  } else if (document.layers){
    itm = document.layers[id];
  }

  if (id == 'planoAcao')
  {
	//  document.getElementById('planoAcaoIframe').style.display = '';
	//  document.getElementById('planoAcaoIframe').visibility = 'show';
  }

  if (!itm) {
   // do nothing
  }
  else if (itm.style) {
    if (itm.style.display == "none") { itm.style.display = ""; }
    
  }
  else { itm.visibility = "show"; }
  }
}