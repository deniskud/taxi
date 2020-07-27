<?php
session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
  <title>PHP File Upload</title>
</head>
<body>
  <?php
    if (isset($_SESSION['message']) && $_SESSION['message'])
    {
      printf('<b>%s</b>', $_SESSION['message']);
      unset($_SESSION['message']);
    }
  ?>
  <form method="POST" action="upload.php" enctype="multipart/form-data">
    <div>
      <span>Upload a File:</span>
      <input type="file" name="uploadedFile" />
    </div>
      <p><input name="operator" type="radio" value="uber">Uber</p>
      <p><input name="operator" type="radio" value="bolt">Bolt</p>
      <p><input name="operator" type="radio" value="uklon" checked>Uklon</p>
      startd date: <input type='date' id='start' name='startperiod' value='<?php echo date("Y-m-d");?>'>
      end date: <input type='date' id='end' name='endperiod' value='<?php echo date("Y-m-d");?>'>
    <input type="submit" name="uploadBtn" value="Upload" />
  </form>
</body>
</html>
