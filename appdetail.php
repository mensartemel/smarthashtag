<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'London')" id="defaultOpen">App Details</button>
  <button class="tablinks" onclick="openCity(event, 'Paris')">Services</button>
  <button class="tablinks" onclick="openCity(event, 'Tokyo')">Settings</button>
</div>

<div id="Details" class="tabcontent">
  <a>This is the app details page.</a>
</div>

<div id="Services" class="tabcontent">
  <a>This is the app services page.</a>
</div>

<div id="Settings" class="tabcontent">
  <a>This is the app settings page.</a>
</div>

<script>
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

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
