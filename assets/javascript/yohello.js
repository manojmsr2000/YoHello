$(document).ready(function() {

	$("#search_text_input").focus(function(){
		if(window.matchMedia("(min-width: 800px)").matches){
			$(this).animate({width: '350px'}, 300);
		}
	});

	$("#search_text_input").focusout(function(){
		if(window.matchMedia("(min-width: 800px)").matches){
			$(this).animate({width: '200px'}, 300);
		}
	});

	//Button for profile post
	$('#submit_profile_post').click(function(){

		$.ajax({
			type: "POST",
			url: "includes/handlers/ajax_submit_profile_post.php",
			data: $('form.profile_post').serialize(),
			success: function(msg) {
				$("#post_form").modal('hide');
				location.reload();
			},
			error: function() {
				alert('Failure');
			}
		});

	});
	$(".main .hamburger").click(function(){
		$("#sidebar").toggleClass("toggled");
	});
});

$(document).click(function(e){
	if(e.target.class != "search_results" && e.target.id != "search_text_input"){
		$(".search_results").html("")
		$(".search_results_footer").html("");
		$(".search_results_footer").toggleClass("search_results_footer_empty");
		$(".search_results_footer").toggleClass("search_results_footer");
	}

	if(e.target.class != "dropdown_data_window"){
		$(".dropdown_data_window").html("");
		$(".dropdown_data_window").css({"padding": "0px", "height": "0px"});
	}
});

function getUsers(value, user){
	$.post('includes/handlers/ajax_friend_search.php', {query:value, userLoggedIn: user}, function(data){
			$(".results").html(data);
	});
}

function getDropdownData(user, type){
	if($(".dropdown_data_window").css("height") == "0px"){
		let pageName;
		if(type == 'notification'){
			pageName = "ajax_load_notifications.php";
			$("span").remove("#unread_notification");
		}
		else if(type == 'message'){
			pageName = "ajax_load_messages.php";
			$("span").remove("#unread_message");
		}

		let ajaxreq = $.ajax({
			url: "includes/handlers/" + pageName,
			type: "POST",
			data: "page=1&userLoggedIn=" + user,
			cache: false,

			success: function(response){
				$(".dropdown_data_window").html(response);
				$(".dropdown_data_window").css({"padding-top": "25px", "height": "300px"});
				$("#dropdown_data_type").val(type);
			}
		});
	}
	else{
		$(".dropdown_data_window").html("");
		$(".dropdown_data_window").css({"padding": "0px", "height": "0px"});
	}
}

function getLiveSeachUsers(value, user){
	$.post("includes/handlers/ajax_search.php", {query: value, userLoggedIn: user}, function(data){
		if($(".search_results_footer_empty")[0]){
			$(".search_results_footer_empty").toggleClass("search_results_footer");
			$(".search_results_footer_empty").toggleClass("search_results_footer_empty");
		}
		$(".search_results").html(data)
		$(".search_results_footer").html("<a href='search.php?q="+value+"'>See all results!</a>");
		if(data == ""){
			$(".search_results_footer").html("");
			$(".search_results_footer").toggleClass("search_results_footer_empty");
			$(".search_results_footer").toggleClass("search_results_footer");
		}
	});
}

jQuery(document).ready(function($) {
  var alterClass = function() {
    var ww = document.body.clientWidth;
    if (ww > 600) {
      $('#sidebar').removeClass('toggled');
			$('#hamburger').css('display', 'none');
			$('.navbar-brand h2').addClass('me-5');
    } else if (ww <= 601) {
      $('#sidebar').addClass('toggled');
			$('#hamburger').css("display", "block");
			$('.navbar-brand h2').removeClass('me-5');
    };
  };
  $(window).resize(function(){
    alterClass();
  });
  //Fire it when the page first loads:
  alterClass();
});
