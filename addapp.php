<form class="form-horizontal" method="post">
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Application Name:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="email" placeholder="New Application" name="appname">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd">Description:</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="pwd" placeholder="Description" name="desc">
    </div>
  </div>
  <div class="form-group">
    <label for="sel2">Mutiple select list (hold shift to select more than one):</label>
    <select multiple class="form-control" id="sel2">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
      <option>5</option>
    </select>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
  </div>
</form>
