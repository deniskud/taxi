<!DOCTYPE html>
<html>
<body>

<form action="uploadok.php" method="post" enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
      <p><input name="operator" type="radio" value="uber">Uber</p>
      <p><input name="operator" type="radio" value="bolt">Bolt</p>
      <p><input name="operator" type="radio" value="uklon" checked>Uklon</p>
      startd date: <input type='date' id='start' name='startperiod' value='<?php echo date("Y-m-d");?>'>
      end date: <input type='date' id='end' name='endperiod' value='<?php echo date("Y-m-d");?>'>

  <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>
