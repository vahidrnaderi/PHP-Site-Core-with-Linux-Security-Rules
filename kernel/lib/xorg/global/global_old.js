var editor;

function replaceAll(txt, replace, withThis) {
	return txt.replace(new RegExp(replace, 'g'),withThis);
}

function stringContains(haystack, needle){
	if(haystack.indexOf(needle) == -1){
		return false;
	}else{
		return true;
	}
} 

function checkBoxToggle(checkBox) {
	if (document.getElementById(checkBox).checked === true)
		document.getElementById(checkBox).value = 1;
	else 
		document.getElementById(checkBox).value = 0;
}

function createEditor(where, appendTo) {
	if (editor)
		return;
	if(appendTo == null){
		appendTo = 'editor';
	}
	var html = document.getElementById(where).innerHTML;

	// Create a new editor inside the <div id="editor">, setting its value to
	// html
	var config = {};
	editor = CKEDITOR.appendTo(appendTo, config, html);
}

function getText(where) {
	if(editor){
// document.getElementById(where).innerHTML = editor.getData(where);
// var html = document.getElementById(where).innerHTML;
// alert(editor.getData(where));
		return replaceAll(editor.getData(where), '&', '%26');
	}else{
		return "";
	}
}

function removeEditor() {
	if (!editor)
		return;

	// Retrieve the editor contents. In an Ajax application, this data would be
	// sent to the server or used in any other way.
	// document.getElementById('editorcontents').innerHTML = editor.getData();
	// document.getElementById('textcontents').style.display = '';

	// Destroy the editor.
	editor.destroy();
	editor = null;
}

// CkFinder
function BrowseServer(functionData, mode) {
	var finder = new CKFinder();
	finder.basePath = 'lib/ckfinder';
	finder.selectActionData = functionData;
	if(mode == 'single'){
		finder.selectActionFunction = SetSingleFileField;
	}else{
		finder.selectActionFunction = SetFileField;
	}
	finder.popup();
}

function SetSingleFileField(fileUrl, data) {

	console.log(data);
	var fileName = fileUrl.replace( /^.*[\/\\]/g, '' ) ;
	document.getElementById('contentPath').value =  fileUrl;
	if (stringContains(data["selectActionData"], "image")){
		document.getElementById('src' + data["selectActionData"]).src = fileUrl;
	}else if(stringContains(data["selectActionData"], "file")){
		document.getElementById('src' + data["selectActionData"]).innerHTML = fileName;
	}
	
}

function SetFileField(fileUrl, data) {

	console.log(data);
	if (stringContains(data["selectActionData"], "image")){
//		alert('URL: ' + fileUrl);
		document.getElementById('imagePath').value =  fileUrl + ',' + document.getElementById('imagePath').value;
		
		document.getElementById('src' + data["selectActionData"]).src = fileUrl;
	}else if(stringContains(data["selectActionData"], "file")){
		var fileName = fileUrl.replace( /^.*[\/\\]/g, '' ) ;
		document.getElementById('filePath').value =  fileUrl + ',' + document.getElementById('filePath').value;
		document.getElementById('src' + data["selectActionData"]).innerHTML = fileName;
	}
}

function createUploader(type, where){
	
	var randomNumber = Math.floor(Math.random()*10001);
	var divTag = document.createElement("div");
	divTag.id = "browseServer";
	divTag.style.cursor= "pointer";
	
/*
	divTag.style  ="background-color:lightgreen;width:80px;height:20px;border:1px solid;";
	.width= "20px";
	divTag.style.height= "20px";
	divTag.style.border= "1px solid";
*/
	divTag.className = "imageGalleryImage";
	
	content = "";
	switch (type) {
		case 'img':
			content +=	'<img id="srcimagePath' + randomNumber + '" src="theme/IdealMart/img/icon/plus.png" width="90%" style="border: 1px solid #000;" onclick="BrowseServer(\'imagePath' + randomNumber + '\', \'\');">';
			break;
		default:
		case 'file':
			content += '<div id="srcfilePath' + randomNumber + '" width="90%" style="direction: ltr; text-align: left; margin: 1px 0px; padding: 1px; border: 1px solid #000;" onclick="BrowseServer(\'filePath' + randomNumber + '\', \'\');"><img src="theme/IdealMart/img/icon/plus.png"></div>';
			break;
	}
	divTag.innerHTML = content;	
	$('#' + where).append(divTag);
// document.getElementById(where).appendChild(divTag);
}

function addToBasket(where){
//	alert ("addToBasket");
	document.getElementById('td' + where).style.background = "#E5ECFC url('theme/icasb/img/icon/basket.png') no-repeat left top"; 
	opacityMan('cell' + where, 20);
}

/*
function addToCompare(where){
	document.getElementById('td' + where).style.background = "#E5ECFC url('theme/icasb/img/icon/compare.png') no-repeat left top"; 
	opacityMan('cell' + where, 20);
}*/

/* Input direction set */
$('input').keyup(function(){
    $this = $(this);
    if($this.val().length == 1)    {
        var x =  new RegExp("[\x00-\x80]+"); 
        var isAscii = x.test($this.val());
        if(isAscii)
            $this.css("direction", "ltr");
        else
            $this.css("direction", "rtl");
    }
});

/* UserMan */
//$('#userRegistration').live('click', function() {
//	$('#coName').fadeOut(function() {
//		$('#firstName').fadeIn();
//	}			
//	);
//	$('#lastName').fadeIn();
//});
//
//$('#coRegistration').live('click', function() {
//	$('#firstName').fadeOut(function() {
//		$('#coName').fadeIn();
//	}			
//	);
//	$('#lastName').fadeOut();
//});

/* Profile */
$('#systemInfoLink').live('click', function(){
	$('#systemInfoLink').css("color","black");
	$('#personalInfoLink').css("color","gray");
	$('#contactInfoLink').css("color","gray");
	$('#permissionInfoLink').css("color","gray");
	$('#changePasswordLink').css("color","gray");
	$('#professionInfoLink').css("color","gray");
	$('#rankInfoLink').css("color","gray");
	$('#rankInfo').hide();
	$('#professionInfo').hide();
	$('#personalInfo').hide(); 
	$('#contactInfo').hide(); 
	$('#changePassword').hide(); 
	$('#permissionInfo').hide(); 
	$('#systemInfo').show(); 
});

$('#personalInfoLink').live('click', function(){
	$('#systemInfoLink').css("color","gray");
	$('#personalInfoLink').css("color","black");
	$('#contactInfoLink').css("color","gray");
	$('#permissionInfoLink').css("color","gray");
	$('#changePasswordLink').css("color","gray");
	$('#professionInfoLink').css("color","gray");
	$('#rankInfoLink').css("color","gray");
	$('#rankInfo').hide();
	$('#professionInfo').hide();
	$('#systemInfo').hide();
	$('#contactInfo').hide();
	$('#permissionInfo').hide();
	$('#changePassword').hide(); 
	$('#personalInfo').show();
});

$('#professionInfoLink').live('click', function(){
	$('#systemInfoLink').css("color","gray");
	$('#personalInfoLink').css("color","gray");
	$('#contactInfoLink').css("color","gray");
	$('#permissionInfoLink').css("color","gray");
	$('#changePasswordLink').css("color","gray");
	$('#professionInfoLink').css("color","black");
	$('#rankInfoLink').css("color","gray");
	$('#rankInfo').hide();
	$('#professionInfo').show();
	$('#systemInfo').hide();
	$('#contactInfo').hide();
	$('#permissionInfo').hide();
	$('#changePassword').hide(); 
	$('#personalInfo').hide();
});

$('#contactInfoLink').live('click', function(){
	$('#systemInfoLink').css("color","gray");
	$('#personalInfoLink').css("color","gray");
	$('#contactInfoLink').css("color","black");
	$('#permissionInfoLink').css("color","gray");
	$('#changePasswordLink').css("color","gray");
	$('#professionInfoLink').css("color","gray");
	$('#rankInfoLink').css("color","gray");
	$('#rankInfo').hide();
	$('#professionInfo').hide();
	$('#personalInfo').hide(); 
	$('#contactInfo').show(); 
	$('#changePassword').hide(); 
	$('#permissionInfo').hide(); 
	$('#systemInfo').hide(); 
});

$('#permissionInfoLink').live('click', function(){
	$('#systemInfoLink').css("color","gray");
	$('#personalInfoLink').css("color","gray");
	$('#contactInfoLink').css("color","gray");
	$('#permissionInfoLink').css("color","black");
	$('#changePasswordLink').css("color","gray");
	$('#professionInfoLink').css("color","gray");
	$('#rankInfoLink').css("color","gray");
	$('#rankInfo').hide();
	$('#professionInfo').hide();
	$('#personalInfo').hide(); 
	$('#contactInfo').hide(); 
	$('#changePassword').hide(); 
	$('#permissionInfo').show(); 
	$('#systemInfo').hide(); 
});

$('#rankInfoLink').live('click', function(){
	$('#systemInfoLink').css("color","gray");
	$('#personalInfoLink').css("color","gray");
	$('#contactInfoLink').css("color","gray");
	$('#permissionInfoLink').css("color","gray");
	$('#changePasswordLink').css("color","gray");
	$('#professionInfoLink').css("color","gray");
	$('#rankInfoLink').css("color","black");
	$('#rankInfo').show();
	$('#professionInfo').hide();
	$('#personalInfo').hide(); 
	$('#contactInfo').hide(); 
	$('#changePassword').hide(); 
	$('#changePassword').hide();
	$('#permissionInfo').hide(); 
	$('#systemInfo').hide(); 
});

$('#changePasswordLink').live('click', function(){
	$('#systemInfoLink').css("color","gray");
	$('#personalInfoLink').css("color","gray");
	$('#contactInfoLink').css("color","gray");
	$('#permissionInfoLink').css("color","gray");
	$('#changePasswordLink').css("color","black");
	$('#professionInfoLink').css("color","gray");
	$('#rankInfoLink').css("color","gray");
	$('#rankInfo').hide();
	$('#professionInfo').hide();
	$('#personalInfo').hide(); 
	$('#contactInfo').hide(); 
	$('#changePassword').show(); 
	$('#changePassword').farajax('loader', '/userMan/v_changePass');
	$('#permissionInfo').hide(); 
	$('#systemInfo').hide(); 
});

$('#editProfileLink').live('click', function(){
	$('.showProfile').hide(); $('.editProfile').show(); $(this).hide(); $('#showProfileLink').show();
	$('#submitProfile').fadeIn();
	if($('#gender').length == 0)
		$('#genderSelectBox').farajax('loader', '/htmlElements/v_selectGender', 'name=gender&selected=' + $('#genderId').val());
	if($('#religion').length == 0)
		$('#religionSelectBox').farajax('loader', '/htmlElements/v_selectReligion', 'name=religion&selected=' + $('#religionId').val());
	if($('#level').length == 0)
		$('#levelSelectBox').farajax('loader', '/htmlElements/v_selectLevel', 'name=level&selected=' + $('#levelId').val());
	if($('#nationality').length == 0)
		$('#nationalitySelectBox').farajax('loader', '/htmlElements/v_selectCountry', 'name=nationality&selected=' + $('#nationalityId').val());
	if($('#state').length == 0)
		$('#stateSelectBox').farajax('loader', '/htmlElements/v_selectState', 'name=state&selected=' + $('#stateId').val());
	if($('#city').length == 0)
		$('#citySelectBox').farajax('loader', '/htmlElements/v_selectCity', 'name=city&sid=' + $('#stateId').val() + '&selected=' + $('#cityId').val());
	if($('#region').length == 0)
		$('#regionSelectBox').farajax('loader', '/htmlElements/v_selectRegion', 'name=region&selected=' + $('#regionId').val());
	if($('#district').length == 0)
		$('#districtSelectBox').farajax('loader', '/htmlElements/v_selectDistrict', 'name=district&selected=' + $('#districtId').val());
	if($('#financialStatus').length == 0)
		$('#statusSelectBox').farajax('loader', '/htmlElements/v_selectStatus', 'name=financialStatus&selected=' + $('#financialStatusId').val());
});

$('#showProfileLink').live('click', function(){
	$('.editProfile').hide(); $('.showProfile').show(); $(this).hide(); $('#editProfileLink').show();
	$('#submitProfile').fadeOut();
});

$('#state').live('change', function(){
	if($(this).val() != ''){
		$('#citySelectBox').farajax('loader', '/htmlElements/v_selectCity', 'name=city&sid=' + $(this).val() + '&selected=' + $('#cityId').val());
//		$('#citySelectBox').farajax('loader', '/htmlElements/v_selectCity', 'name=city&sid=' + $(this).val() + '&selected=' + $('#profileId/*#cityId*/').val());
	}
});

$('#city').live('change', function(){
	if($(this).val() != ''){
		$('#regionSelectBox').farajax('loader', '/htmlElements/v_selectRegion', 'name=region&selected=' + $('#regionId').val());
//		$('#regionSelectBox').farajax('loader', '/htmlElements/v_selectRegion', 'name=region&sid=' + $(this).val() + '&selected=' + $('#profileId/*#regionId*/').val());		
	}
});

$('#region').live('change', function(){
////	alert ($(this).val());
	if($(this).val() != ''){
////		alert ('name=district&city=' + $('#city').val() + '&region=' + $(this).val() + '&selected=' + $('#districtId').val());
		$('#districtSelectBox').farajax('loader', '/htmlElements/v_selectDistrict', 'name=district&city=' + $('#city').val() + '&region=' + $(this).val() + '&selected=' + $('#districtId').val());
//		$('#districtSelectBox').farajax('loader', '/htmlElements/v_selectDistrict', 'name=district&city=' + $('#city').val() + '&region=' + $(this).val() + '&selected=' + $('#profileId/*#districtId*/').val());
	}
});

/* Post Module */
function postModuleInitial(){
	
// createEditor('description', 'editorFullText');
// $('fullText').hide();
	
	$('#category').mcDropdown('#categorymenu');
	
	/* VideoContent Slide */
	$('#videoContent').hover(function() {
		$(this).animate({opacity:'1.0'});
		$(this).hover(function() {
		}, function() {
			$(this).animate({ opacity:'0.8' });
		});
	});
	
	/* ImageContent Slide */
	$('#imageContent').hover(function() {
		$(this).animate({opacity:'1.0'});
		$(this).hover(function() {
		}, function() {
			$(this).animate({ opacity:'0.8' });
		});
	});
	
	/* VoiceContent Slide */
	$('#voiceContent').hover(function() {
		$(this).animate({opacity:'1.0'});
		$(this).hover(function() {
		}, function() {
			$(this).animate({ opacity:'0.8' });
		});
	});
	
	/* TextContent Slide */
	$('#textContent').hover(function() {
		$(this).animate({opacity:'1.0'});
		$(this).hover(function() {
		}, function() {
			$(this).animate({opacity:'0.8'});
		});
	});
	
	/* VideoContent Click */
	$('#videoContent').live('click', function() {
		$('#postContentEntry').slideDown('slow'); 
		$(this).css({background: '#efc4c4'});
		$('#imageContent').css({background: '#efefef'});
		$('#voiceContent').css({background: '#efefef'});
		$('#textContent').css({background: '#efefef'});
		$('#contentType').val('video');
		$('#uploader').fadeIn('slow');
	});
	
	/* ImageContent Click */
	$('#imageContent').live('click', function() {
		$('#postContentEntry').slideDown('slow'); 
		$('#videoContent').css({background: '#efefef'});
		$(this).css({background: '#efc4c4'});
		$('#voiceContent').css({background: '#efefef'});
		$('#textContent').css({background: '#efefef'});
		$('#contentType').val('image');
		$('#uploader').fadeIn('slow');
	});
	
	/* VoiceConten Click */
	$('#voiceContent').live('click', function() {
		$('#postContentEntry').slideDown('slow'); 
		$('#videoContent').css({background: '#efefef'});
		$('#imageContent').css({background: '#efefef'});
		$(this).css({background: '#efc4c4'});
		$('#textContent').css({background: '#efefef'});
		$('#contentType').val('voice');
		$('#uploader').fadeIn('slow');
	});
	
	/* TextContent Click */
	$('#textContent').live('click', function() {
		$('#postContentEntry').slideDown('slow'); 
		$('#videoContent').css({background: '#efefef'});
		$('#imageContent').css({background: '#efefef'});
		$('#voiceContent').css({background: '#efefef'});
		$(this).css({background: '#efc4c4'});
		$('#contentType').val('text');
		$('#uploader').fadeOut('slow');
	});
	
	/* Time Slide */
	$('#setTime').live('click', function() {
		$('#sourcePanel').slideUp();
		$('#attachPanel').slideUp();
		$('#timePanel').slideDown('slow');
	});
	
	/* Source Slide */
	$('#setSource').live('click', function() {
		$('#timePanel').slideUp();
		$('#attachPanel').slideUp();
		$('#sourcePanel').slideDown('slow');
	});
	
	/* Attach Slide */
	$('#setAttach').live('click', function() {
		$('#timePanel').slideUp();
		$('#sourcePanel').slideUp();
		$('#attachPanel').slideDown('slow');
	});
}