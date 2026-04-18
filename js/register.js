		$(window).load(function(){
		function checkPasswordMatch() {
			var password = $("#password").val();
			var confirmPassword = $("#rpassword").val();

			if (password != confirmPassword)
				$("#checkpassword").html(no_password_r);
			else
				$("#checkpassword").html("");
		}

		$(document).ready(function () {
		   $("#rpassword").keyup(checkPasswordMatch);
		});
		
		function checkUsername() {
			var name = $("#username").val();
			var regex = /^[0-9a-zA-Z]*$/;

			if(!regex.test(name))
				$("#checkname").html(no_special_chars);
			else
				$("#checkname").html("");
		}

		$(document).ready(function () {
		   $("#username").keyup(checkUsername);
		});	
		
		});