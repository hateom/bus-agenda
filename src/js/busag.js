function val_form_login(formData, jqForm, options) { 
    var form = jqForm[0]; 
    if (!form.passwd.value) { 
        alert('Please enter a value for Password'); 
        return false; 
    } 
    return true;
}

 $(document).ready(function(){
  $(".error a").click(function(){
     $(".error").fadeOut("fast");
     return false;
  });
  
  $(".notify a").click(function(){
     $(".notify").fadeOut("fast");
     return false;
  });

  $("#add_bs").click(function(){
    $("#form_add_line").hide();
	$.ajax({
	  url: "bs.php?n=route[]",
	  type: "GET",
	  cache: false,
	  success: function(html) {
        $("#form_add_line").append(html);
      }
    });
	$("#form_add_line").fadeIn("slow");
  });
  
  $("#add_hour").click(function(){
    $("#hours").hide();
	$.ajax({
	  url: "hour.php?n=nhour[]",
	  type: "GET",
	  cache: false,
	  success: function(html) {
        $("#hours").append(html);
      }
    });
	$("#hours").fadeIn("slow");
  });

  $("#form_login").hide();
  $("<a id='login_dom' href='#'>Panel administratora</a>").appendTo("#login");
  $("#login_dom").click(function(){
    $("#form_login").fadeIn("slow");
    $("#pwd_in").focus();
    $(this).hide();
    return false;
  });

  //$("#form_login").ajaxForm( { beforeSubmit: val_form_login } );
 });
