$("#inscrire").click(function () {
  var radioValue = $("input[name=radio]:checked").val();
  console.log(radioValue);
  if (radioValue != undefined) {
    $("input[name=radio]").prop('checked', false);
    window.location.href = "create-compte?identifiant=" + radioValue;
  } else {
    $("#warning").css({"display" : "block", "color" : "red", "fontSize" : "18px"}).fadeOut(10000);
  }
});

$("#annuler").click(function(){
  $("input[name=radio]").prop('checked', false);
});