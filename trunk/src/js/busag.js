 $(document).ready(function(){
  $(".error a").click(function(){
     $(".error").fadeOut("fast");
     return false;
  });
  $("#form_conn").ajaxForm( { beforeSubmit: val_form_conn } );
  $("#form_login").ajaxForm( { beforeSubmit: val_form_login } );
 });

function val_form_login(formData, jqForm, options) { 
    var form = jqForm[0]; 
    if (!form.x.value ) { 
        return false; 
    } 
}


function val_form_login(formData, jqForm, options) { 
    var form = jqForm[0]; 
    if (!form.passwd.value) { 
        alert('Please enter a value for Password'); 
        return false; 
    } 
    alert('Both fields contain values.'); 
}
