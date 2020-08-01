<?php
session_start();
$ok='0';
$message = ''; 
if (isset($_POST['operator'])) $agregator=$_POST['operator'];
if (isset($_POST['startp']))   $startp=$_POST['startp'];
if (isset($_POST['endp']))     $endp=$_POST['endp'];
//echo 'start:'.$startp.' endp:'.$endp.'<br>';


if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload')
{
  if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK)
  {
    // get details of the uploaded file
    $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
    $fileName = $_FILES['uploadedFile']['name'];
    $fileSize = $_FILES['uploadedFile']['size'];
    $fileType = $_FILES['uploadedFile']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // sanitize file-name
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

    // check if file has one of the following extensions
    $allowedfileExtensions = array('csv', 'xlsx', 'CSV', 'XLSX', 'xls', 'XLS');

    if (in_array($fileExtension, $allowedfileExtensions))
    {
      // directory in which the uploaded file will be moved
      $uploadFileDir = './uploaded_files/';
      $dest_path = $uploadFileDir . $newFileName;

      if(move_uploaded_file($fileTmpPath, $dest_path)) 
      {
        $message ='File is successfully uploaded.';
        $ok='1';
      }
      else 
      {
        $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
      }
    }
    else
    {
      $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
    }
  }
  else
  {
    $message = 'There is some error in the file upload. Please check the following error.<br>';
    $message .= 'Error:' . $_FILES['uploadedFile']['error'];
  }
}

//$_SESSION['message'] = $message;
if ($ok=='1') {echo '<font color="#2222aa">';}
else {echo '<font color="#bb1111">';}
echo $message.'<br></font>';

echo '<font face="Arial">File &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp :<b>'.$fileName.'</b> <br>Format &nbsp&nbsp:<b>'.$agregator.'</b><br>  saved as:<b>'.$newFileName.'</b></font><br>';
echo 'From&nbsp&nbsp&nbsp&nbsp:<b>'.$startp.'</b><br>To &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:<b>'.$endp.'</b><br>';
echo '<br>---------<br>';

$execstring='';
$execstr2='';
$execstring='node /var/www/js/'.$agregator.'2db.js /var/www/html/zmey/uploaded_files/'.$newFileName.' '.$startp.' '.$endp;
$execstr2='rm -y '.$newFileName;
//if ($agregator='uber') $execstring.
echo 'Try executing '.$execstring.'<br>';
$ret=exec($execstring);
//echo '<p><a href="http://taxi.4okna.com" target="table"> <button> <--- Back</button></a></p>';
exec(execstr2);
//header("Location: index.php");
?>