var editor;

function changePosition (sourceParam, destinationParam){
	var source = $(sourceParam).offset();
	var height = $(sourceParam).height();
	var width = $(sourceParam).width();
	$(destinationParam).width(width).height(height);
	$(destinationParam).offset({ top: source.top, left: source.left});
}

function replaceAll(e, t, n) {
	return e.replace(new RegExp(t, "g"), n);
}
function stringContains(e, t) {
	if (e.indexOf(t) == -1) {
		return false;
	} else {
		return true;
	}
}
function checkBoxToggle(e) {
	if (document.getElementById(e).checked === true)
		document.getElementById(e).value = 1;
	else
		document.getElementById(e).value = 0;
}
function createEditor(e, t) {
	if (editor)
		return;
	if (t == null) {
		t = "editor";
	}
	var n = document.getElementById(e).innerHTML;
	// Create a new editor inside the <div id="editor">, setting its value to
	// html
	var r = {};
	editor = CKEDITOR.appendTo(t, r, n);
}
function getText(e) {
	if (editor) {
// document.getElementById(where).innerHTML = editor.getData(where);
// var html = document.getElementById(where).innerHTML;
// alert(editor.getData(where));
		return replaceAll(editor.getData(e), "&", "%26");
	} else {
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
function browseServer(e) {
	var n = new CKFinder;
	n.basePath = "lib/ckfinder";
	n.selectActionData = e;
	n.selectActionFunction = setFileField;
//	if (t == "single") {
//		n.selectActionFunction = SetSingleFileField
//	} else {
//		n.selectActionFunction = SetFileField
//	}
	n.popup();
}
//function SetSingleFileField(e, t) {
//	console.log(t);
//	var n = e.replace(/^.*[\/\\]/g, "");
//	document.getElementById("contentPath").value = e;
//	if (stringContains(t["selectActionData"], "image")) {
//		document.getElementById("src" + t["selectActionData"]).src = e
//	} else if (stringContains(t["selectActionData"], "file")) {
//		document.getElementById("src" + t["selectActionData"]).innerHTML = n
//	}
//}
function setFileField(e, t) {
	console.log(t);
	// Select single of multi file upload
	if (stringContains(t["selectActionData"], 'single')) {
		document.getElementById(t["selectActionData"]).value = e;
	}else if(stringContains(t["selectActionData"], 'multi')){
		document.getElementById(t["selectActionData"]).value = e + "," + document.getElementById(t["selectActionData"]).value;
	}
	// Select file type
	if (stringContains(t["selectActionData"], 'image')) {
		document.getElementById('show-' + t["selectActionData"]).src = e;
	} else if (stringContains(t["selectActionData"], 'file')) {
		var n = e.replace(/^.*[\/\\]/g, "");		
		document.getElementById('show-' + t["selectActionData"]).innerHTML = n;
	}
}
function createUploader(e, t) {
	var n = Math.floor(Math.random() * 10001);
	var r = document.createElement("div");
	r.id = "browseServer";
	r.style.cursor = "pointer";
/*
	divTag.style  ="background-color:lightgreen;width:80px;height:20px;border:1px solid;";
	.width= "20px";
	divTag.style.height= "20px";
	divTag.style.border= "1px solid";
*/
	r.className = "imageGalleryImage";
	content = "";
	switch (e) {
	case "img":
		content += '<img id="srcimagePath'
				+ n
				+ '" src="theme/iCasb/img/icon/plus.png" width="90%" style="border: 1px solid #000;" onclick="browseServer(\'imagePath'
				+ n + "', '');\">";
		break;
	default:
	case "file":
		content += '<div id="srcfilePath'
				+ n
				+ '" width="90%" style="direction: ltr; text-align: left; margin: 1px 0px; padding: 1px; border: 1px solid #000;" onclick="browseServer(\'filePath'
				+ n
				+ "', '');\"><img src=\"theme/iCasb/img/icon/plus.png\"></div>";
		break;
	}
	r.innerHTML = content;
	$("#" + t).append(r);
// document.getElementById(where).appendChild(divTag);
}

//****************************************************************************************************

/****   sample from digiSeo.ir  ***********
$(document).on("click", '#postCategory', function (e) {
	$('#postCategory li').removeClass('selected');
    $(e.target).closest("li").addClass("selected");
//    alert('id: ' + $(e.target).closest('li').attr('id'));
    $('#content').farajax('loader', '/post/c_showListObject/category=' + $(e.target).closest('li').attr('id'));
});

$(document).on('click', "#creditInfoLink", function() {
	$("#personalInfo").hide();
	$("#contactInfo").hide();
	$("#changePassword").hide();
	$("#creditInfo").show();
	$('#creditInfo').farajax('loader', 'credit/c_listObject');
	$("#systemInfo").hide();
	$("#personalPage").hide();
});
$(document).on('click', "#changePasswordLink", function() {
	$("#personalInfo").hide();
	$("#contactInfo").hide();
	$("#changePassword").show();
	$("#changePassword").farajax("loader", "/userMan/v_changePass");
	$("#creditInfo").hide();
	$("#systemInfo").hide();
	$("#personalPage").hide();
});
$(document).on(
		'click',
		"#editProfileLink",
		function() {
			$(".showProfile").hide();
			$(".editProfile").show();
			$(this).hide();
			$("#showProfileLink").show();
			$("#submitProfile").fadeIn();
			if ($("#gender").length == 0)
				$("#genderSelectBox").farajax("loader",
						"/htmlElements/v_selectGender",
						"name=gender&selected=" + $("#genderId").val());
			if ($("#religion").length == 0)
				$("#religionSelectBox").farajax("loader",
						"/htmlElements/v_selectReligion",
						"name=religion&selected=" + $("#religionId").val());
			if ($("#level").length == 0)
				$("#levelSelectBox").farajax("loader",
						"/htmlElements/v_selectLevel",
						"name=level&selected=" + $("#levelId").val());
			if ($("#nationality").length == 0)
				$("#nationalitySelectBox").farajax(
						"loader",
						"/htmlElements/v_selectCountry",
						"name=nationality&selected="
								+ $("#nationalityId").val());
			if ($("#state1").length == 0)
				$("#stateSelectBox").farajax("loader",
						"/htmlElements/v_selectState",
						"name=state1&selected=" + $("#stateId").val());
			if ($("#financialStatus").length == 0)
				$("#statusSelectBox").farajax(
						"loader",
						"/htmlElements/v_selectStatus",
						"name=financialStatus&selected="
								+ $("#financialStatusId").val())
		});

$(document).on('change', '#state1', function() {
	if ($(this).val() != "") {
		$("#citySelectBox").farajax(
				"loader",
				"/htmlElements/v_selectCity",
				"name=city1&sid=" + $(this).val() + "&selected="
						+ $("#cityId").val())
	}
});
$(document).on('change', '#city1', function() {
	if ($(this).val() != "") {
		$("#regionSelectBox").farajax("loader",
				"/htmlElements/v_selectRegion",
				"name=region1&selected=" + $("#regionId").val())
	}
});
$(document).on('change', '#region1', function() {
	if ($(this).val() != "") {
		$("#districtSelectBox").farajax(
				"loader",
				"/htmlElements/v_selectDistrict",
				"name=district1&city=" + $("#city1").val() + "&region="
						+ $(this).val() + "&selected="
						+ $("#districtId1").val())
	}
});
$(document).on('click', "#paginateTools > #ptSearch > img", function() {
	if ($("#paginateTools").width() < 200) {
		$(this).fadeOut("slow", function() {
			$("#universalSearch").fadeIn("slow")
		});
		$("#paginatePage").animate({
			width : "50%"
		}, {
			queue : false,
			duration : 500
		});
		$("#paginateTools").animate({
			width : "30%"
		}, {
			queue : false,
			duration : 500
		})
	}
});

$("#universalSearch").keyup(function() {
	var e = $(this).val();
	if (e.length > 0) {
	} else {
		$("#universalSearch").fadeOut("slow", function() {
			$("#paginateTools > #ptSearch > img").fadeIn("slow")
		});
		$("#paginatePage").animate({
			width : "80%"
		}, {
			queue : false,
			duration : 500
		});
		$("#paginateTools").animate({
			width : "10%"
		}, {
			queue : false,
			duration : 500
		})
	}
});

$(document).on('click', '#closeModal', function (){
	$('#modalWindow').fadeOut('slow', function(){
		$('#modalMask').fadeOut('slow');
		enable_scroll();
	});
});
*/
//***************************************************************#######################**********************************************
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
$(document).on('click', "#systemInfoLink", function() {
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

$(document).on('click', "#personalInfoLink", function() {
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

$(document).on('click', "#professionInfoLink", function() {
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

$(document).on('click', "#contactInfoLink", function() {
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

$(document).on('click', "#permissionInfoLink", function() {
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

$(document).on('click', "#rankInfoLink", function() {
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

$(document).on('click', "#changePasswordLink", function() {
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

$(document).on('click', "#editProfileLink", function() {
	$('.showProfile').hide(); $('.editProfile').show(); 
	$(this).hide(); 
	$('#showProfileLink').show();
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

$(document).on('click', "#showProfileLink", function() {
	$('.editProfile').hide(); $('.showProfile').show(); $(this).hide(); $('#editProfileLink').show();
	$('#submitProfile').fadeOut();
});

$(document).on('change', "#state", function() {
	if($(this).val() != ''){
		$('#citySelectBox').farajax('loader', '/htmlElements/v_selectCity', 'name=city&sid=' + $(this).val() + '&selected=' + $('#cityId').val());
//		$('#citySelectBox').farajax('loader', '/htmlElements/v_selectCity', 'name=city&sid=' + $(this).val() + '&selected=' + $('#profileId/*#cityId*/').val());
	}
});

$(document).on('change', "#city", function() {
	if($(this).val() != ''){
		$('#regionSelectBox').farajax('loader', '/htmlElements/v_selectRegion', 'name=region&selected=' + $('#regionId').val());
//		$('#regionSelectBox').farajax('loader', '/htmlElements/v_selectRegion', 'name=region&sid=' + $(this).val() + '&selected=' + $('#profileId/*#regionId*/').val());		
	}
});

$(document).on('change', "#region", function() {
////	alert ($(this).val());
	if($(this).val() != ''){
////		alert ('name=district&city=' + $('#city').val() + '&region=' + $(this).val() + '&selected=' + $('#districtId').val());
		$('#districtSelectBox').farajax('loader', '/htmlElements/v_selectDistrict', 'name=district&city=' + $('#city').val() + '&region=' + $(this).val() + '&selected=' + $('#districtId').val());
//		$('#districtSelectBox').farajax('loader', '/htmlElements/v_selectDistrict', 'name=district&city=' + $('#city').val() + '&region=' + $(this).val() + '&selected=' + $('#profileId/*#districtId*/').val());
	}
});


//**********************************************************************************************************************

/* Post Module */
function postModuleInitial() {
// createEditor('description', 'editorFullText');
// $('fullText').hide();
	$("#category").mcDropdown("#categorymenu");
	$("#videoContent").hover(function() {
		$(this).animate({
			opacity : "1.0"
		});
		$(this).hover(function() {
		}, function() {
			$(this).animate({
				opacity : "0.8"
			});
		});
	});
	
	/* ImageContent Slide */	
	$("#imageContent").hover(function() {
		$(this).animate({
			opacity : "1.0"
		});
		$(this).hover(function() {
		}, function() {
			$(this).animate({
				opacity : "0.8"
			});
		});
	});
	
	/* VoiceContent Slide */
	$("#voiceContent").hover(function() {
		$(this).animate({
			opacity : "1.0"
		});
		$(this).hover(function() {
		}, function() {
			$(this).animate({
				opacity : "0.8"
			});
		});
	});
	
	/* TextContent Slide */
	$("#textContent").hover(function() {
		$(this).animate({
			opacity : "1.0"
		});
		$(this).hover(function() {
		}, function() {
			$(this).animate({
				opacity : "0.8"
			});
		});
	});
	
	/* VideoContent Click */
	$(document).on('click', "#videoContent", function() {
		$("#postContentEntry").slideDown("slow");
		$(this).css({
			background : "#efc4c4"
		});
		$("#imageContent").css({
			background : "#efefef"
		});
		$("#voiceContent").css({
			background : "#efefef"
		});
		$("#textContent").css({
			background : "#efefef"
		});
		$("#contentType").val("video");
		$("#uploader").fadeIn("slow");
	});
	
	/* ImageContent Click */
	$(document).on('click', "#imageContent", function() {
		$("#postContentEntry").slideDown("slow");
		$("#videoContent").css({
			background : "#efefef"
		});
		$(this).css({
			background : "#efc4c4"
		});
		$("#voiceContent").css({
			background : "#efefef"
		});
		$("#textContent").css({
			background : "#efefef"
		});
		$("#contentType").val("image");
		$("#uploader").fadeIn("slow");
	});
	
	/* VoiceConten Click */
	$(document).on('click', "#voiceContent", function() {
		$("#postContentEntry").slideDown("slow");
		$("#videoContent").css({
			background : "#efefef"
		});
		$("#imageContent").css({
			background : "#efefef"
		});
		$(this).css({
			background : "#efc4c4"
		});
		$("#textContent").css({
			background : "#efefef"
		});
		$("#contentType").val("voice");
		$("#uploader").fadeIn("slow");
	});
	
	/* TextContent Click */
	$(document).on('click', "#textContent", function() {
		$("#postContentEntry").slideDown("slow");
		$("#videoContent").css({
			background : "#efefef"
		});
		$("#imageContent").css({
			background : "#efefef"
		});
		$("#voiceContent").css({
			background : "#efefef"
		});
		$(this).css({
			background : "#efc4c4"
		});
		$("#contentType").val("text");
		$("#uploader").fadeOut("slow");
	});
	
	/* Time Slide */
	$(document).on('click', "#setTime", function() {
		$("#sourcePanel").slideUp();
		$("#attachPanel").slideUp();
		$("#timePanel").slideDown("slow");
	});
	
	/* Source Slide */
	$(document).on('click', "#setSource", function() {
		$("#timePanel").slideUp();
		$("#attachPanel").slideUp();
		$("#sourcePanel").slideDown("slow");
	});
	
	/* Attach Slide */
	$(document).on('click', "#setAttach", function() {
		$("#timePanel").slideUp();
		$("#sourcePanel").slideUp();
		$("#attachPanel").slideDown("slow");
	});
}