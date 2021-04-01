$(document).ready(function(){
  //on click signup
  $("#signup").click(function(){
    $("#first").slideUp("fast",function(){
      $("#second").slideDown("fast");
    });
  });

  $("#signin").click(function(){
    $("#second").slideUp("fast",function(){
      $("#first").slideDown("fast");
    });
  });
});
