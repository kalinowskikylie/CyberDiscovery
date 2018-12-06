function myFileBrowser (field_name, url, type, win) {
	var pagereferer = $('#referer').attr('name');
	var campreferer = $('#referer').attr('camp');
	if(pagereferer == 'index'){
		var path_to_uploads = "http://cyberdiscovery.latech.edu/db/home_uploads/";
	} else if(pagereferer == 'blog'){
		var path_to_uploads = "http://cyberdiscovery.latech.edu/camps/"+ campreferer +"/blog_uploads/";
	} else if (pagereferer == 'assignments'){
		var path_to_uploads = "http://cyberdiscovery.latech.edu/camps/"+ campreferer +"/assignments_uploads/";
	} else {
		var path_to_uploads = "";
	}
	//alert("Field_Name: " + field_name + "\nnURL: " + url + "\nnType: " + type + "\nnWin: " + win); // debug/testing
	var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
	x_norm = x/2-300;
	y_norm = y/2-200;
	$('body').append('<div id="upload_modal" style="position:fixed; top:'+ y_norm +'px; left:'+ x_norm +'px; background-color:#666; z-index:300011; width:600px; padding:7px;"><div style="background-color:#EEE;padding:8px;"><h1>File Uploader</h1><form>Choose an item to upload.<br /><input type="button" id="upload_button" value="Upload" /> <a href="#" onClick="upload_modal_close()">Cancel</a></form></div></div>');

	// Do it after the DOM is ready for manipulations
	// Use $(document).ready - jquery
	// document.observe("dom:loaded" - prototype
	new AjaxUpload('upload_button', {action: '/cyberdiscovery/blog_editor/ajax_uploader/upload.php?referer=' + pagereferer +'&camp='+ campreferer, onComplete: function(file,response){upload_modal_close();win.document.getElementById(field_name).value = path_to_uploads + file;win.ImageDialog.showPreviewImage(win.document.getElementById(field_name).value);}});
}

function upload_modal_close (){
	$('#upload_modal').remove();
}