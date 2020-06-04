var header = document.getElementById("vertical-menu");
var btns = header.getElementsByClassName("menu-btn");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
  var current = document.getElementsByClassName("active");
  current[0].className = current[0].className.replace(" active", "");
  this.className += " active";
  });
}
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}


/* Jquery Disable Button if Textarea is empty */

$(document).ready(function () {
    $('button[type="submit"]').attr('disabled', true);
    $('input[type="text"],textarea').on('keyup', function () {
        var text_value = $('input[name="appname"]').val();
        var text_value2 = $('input[name="callbackurl"]').val();
        var text_value3 = $('input[name="desc"]').val();
        $('input[type="submit"]').attr('disabled', true);
        if (text_value != '' || text_value2 != '' || text_value3 != '') {
            $('button[type="submit"]').attr('disabled', false);
        } else {
            $('button[type="submit"]').attr('disabled', true);
        }
    });
});
