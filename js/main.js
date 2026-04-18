// JavaScript Document
$(document).ready(function() {
	
	jQuery.fn.exists = function(){return jQuery(this).length>0;};
	
	// check pws for valid characters
	function hasValidChar(strPass) {
		// check if str contains special characters
		var only_this = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.$!;:-_#';
		for (var i=0;i<strPass.length;i++) {
			//if (only_this.indexOf(strPass.charAt(i))<0) return false;
		}
		return true;
	}
	
	// check for secure pwd
	function checkPass(strPass,minLen) {
		var sec = 0;
		var check = 100;
		var steps = 7;
		var checkByStep = check / steps;
	
		var strToCheck = '0123456789'; // check if numbers
		if (contains(strPass, strToCheck)) { sec++ }
		strToCheck = 'abcdefghijklmnopqrstuvwxyz'; // check if lowercase letters
		if (contains(strPass, strToCheck)) { sec++ }
		strToCheck = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // check if uppercase letters
		if (contains(strPass, strToCheck)) { sec++ }
		//strToCheck = '.$!;:-_#'; // check if special characters
		//if (contains(strPass, strToCheck)) { sec++ }
	
		// check if at least one uppercase AND one lowercase AND one number
		if (strPass.length < 6) sec = 0;
		if (strPass.length >= 6) sec++;
		if (strPass.length >= 8) sec++;
		if (strPass.length >= 10) sec++;
	
		var nCount = sec * checkByStep;
	
		if (nCount > check) nCount = check;
	
		return Math.ceil(nCount);
	}
	// check if string in pattern
	function contains(strText, strPattern) {
		for (i = 0; i < strText.length; i++) {
			if (strPattern.indexOf(strText.charAt(i)) > -1) return true;
		}
		return false;
	}
	
	$('input[type=password], input[type=text], textarea').each(function() {
		var default_value = this.value;
		$(this).focus(function() {
			if(this.value == default_value) {
				this.value = '';
			}
		});
		$(this).blur(function() {
			if(this.value == '') {
				this.value = default_value;
			}
		});
	});
	
	$("#registerForm").validationEngine({
		validationEventTriggers:"keyup blur", 
		inlineValidation: true
	});
	$('#loginForm, #pwlostForm, #changepwForm, #emailChangeForm, #resendactivForm, #lostPasswordCodeForm').validationEngine
	({																									 					
	 	validationEventTriggers:"blur",
		inlineValidation: true
	});
	
	$('#registerForm input').not('#captcha').keyup(function() { 
											//console.log($.validationEngine.loadValidation(this));
		if (!$.validationEngine.loadValidation(this)) {
			if (!$(this).parent().has('.valid-check').length){
				$(this).after('<div class="valid-check"></div>');
			}
		}
		else {
			$(this).parent().find('.valid-check').remove();
		}
	});
	
	
	//CHECKBOX bei "checked" gr�n machen
 	var checkSel = function () { 
    	$('#checkerror label').toggleClass('green'); 
	}
	if ( $("#tac:checked").length) checkSel();
	$("#tac").click(checkSel);

	// check password and show errors and secure bar
	var ratio = '';
	var pwdMinLen = 8;
	$('#password, #newPassword').keyup(function(){
		$('#validChar').text('');
		var strPass = $(this).val();
		if (!hasValidChar(strPass)) {
			$('#validChar').text($('#txtInvalidChar').text());
			return;
		}
		if (strPass.length >= 8) { 
			$('#securePwd .valid-icon').removeClass('invalid');
			$('#securePwd').closest('.formError').addClass('valid');
			
		}
		else{ 
			$('#securePwd .valid-icon').addClass('invalid');
			$('#securePwd').closest('.formError').removeClass('valid');
		}
		
		ratio = checkPass($(this).val(),pwdMinLen);
		if (ratio) {
			$('#securePwdBar').css({width: ratio+'%'});
			if (ratio > 69) {
				$('#securePwd #securePwdBar').css('background-position', '0 -39px');
			} else if (ratio > 41) {
				$('#securePwd #securePwdBar').css('background-position', '0 -26px');
			} else if (ratio < 41) {
				$('#securePwd #securePwdBar').css('background-position', '0px 0px !important');
			} else {$('#securePwd #securePwdBar').css('background-position', '0px 0px');}
		} else {
			$('#securePwdBar').css({width: 0});
			$('#securePwd .valid-icon').addClass('invalid');
		}
		if ( (ratio > 49) && (strPass.length < 5)) {
			$('#securePwdBar').css({'width':'48px', 'background-position':'0px 0px'});
		}
	});
		
	$('#submitBtn').click(function(){
		var success = $(this).parent().validationEngine({returnIsValid:true});
		if (success == true ) {		}
	});
});


function showScreenshots(number)
{
	number = parseInt(number);
	var screenShotPath = "img/screenshots/index.html";
	Slimbox.open( [ [screenShotPath + "screenshot-gallery-1.jpg", ""], [screenShotPath + "screenshot-gallery-2.jpg", ""], [screenShotPath + "screenshot-gallery-3.jpg", ""], [screenShotPath + "screenshot-gallery-4.jpg", ""], [screenShotPath + "screenshot-gallery-5.jpg", ""],[screenShotPath + "screenshot-gallery-6.jpg", ""],[screenShotPath + "screenshot-gallery-7.jpg", ""],[screenShotPath + "screenshot-gallery-8.jpg", ""] ], number );
}
function showIndexScreenshots(number)
{
	number = parseInt(number);
	var screenShotPath = "img/screenshots/index.html";
	Slimbox.open( [ [screenShotPath + "screenshot-index-1.jpg", ""], [screenShotPath + "screenshot-index-2.jpg", ""], [screenShotPath + "screenshot-index-3.jpg", ""], [screenShotPath + "screenshot-index-4.jpg", ""] ], number  );
}
function showWallpapers(number)
{
	number = parseInt(number);
	var wallPaperPath = "img/wallpapers/index.html";
	Slimbox.open( [ [wallPaperPath + "wallpaper-1.jpg", ""],[wallPaperPath + "wallpaper-2.jpg", ""],[wallPaperPath + "wallpaper-3.jpg", ""],[wallPaperPath + "wallpaper-4.jpg", ""],[wallPaperPath + "wallpaper-5.jpg", ""],[wallPaperPath + "wallpaper-6.jpg", ""],[wallPaperPath + "wallpaper-7.jpg", ""],[wallPaperPath + "wallpaper-8.jpg", ""] ], number );
}
function submitEnter(e, form)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		document.forms[form].submit();
		return false;
	}
	else
		return true;
}
function setCookie(name,value,expiredays) {
	var todayDate = new Date();
	todayDate.setDate( todayDate.getDate() + expiredays );
	document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}
