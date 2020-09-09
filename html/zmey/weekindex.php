<?php
session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
  <title>WEEK Upload</title>
</head>
<body>
<a href=index.php><button> на дневную</button></a>

<!--
  <?php
/*
    if (isset($_SESSION['message']) && $_SESSION['message'])
    {
      printf('<b>%s</b>', $_SESSION['message']);
      unset($_SESSION['message']);
    }
*/
  ?>
-->


  <form method="POST" action="weekupload.php" enctype="multipart/form-data">
    <div>
      <span>Upload a File:</span>
      <input type="file" name="uploadedFile" />
    </div>
      <p><input name="operator" type="radio" value="uber" checked>Uber (.csv)</p>
      <p><input name="operator" type="radio" value="bolt" >Bolt (.csv)</p>
      <p><input name="operator" type="radio" value="uklon" checked>Uklon (.xlsx)</p>
      <p><input name="operator" type="radio" value="cash" >Cash (.xlsx)</p>

      startd date: <input type='date' id='start' name='startp' value='<?php echo date("Y-m-d");?>'>
      end date: <input type='date' id='end' name='endp' value='<?php echo date("Y-m-d");?>'>
    <input type="submit" name="uploadBtn" value="Upload" />
  </form>
</div>
<script>
  function exceller() {
    var uri = 'data:application/vnd.ms-excel;base64,',
      template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
      base64 = function(s) {
        return window.btoa(unescape(encodeURIComponent(s)))
      },
      format = function(s, c) {
        return s.replace(/{(\w+)}/g, function(m, p) {
          return c[p];
        })
      }
    var toExcel = document.getElementById("toExcel").innerHTML;
    var ctx = {
      worksheet: name || '',
      table: toExcel
    };
    var link = document.createElement("a");
    link.download = "export.xls";
    link.href = uri + base64(format(template, ctx))
    link.click();
  }
</script>




</body>
</html>
