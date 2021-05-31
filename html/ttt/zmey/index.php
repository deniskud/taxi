<?php
session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
  <title>DAY Upload</title>
</head>
<body>
<a href=weekindex.php><button> на недельную</button></a>


  <form method="POST" action="upload.php" enctype="multipart/form-data">
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


<?php

$dbh = mysqli_connect("127.0.0.1", "zmey", "kalina", "taxi");
if (!$dbh) {
    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

mysqli_query($dbh,"SET NAMES 'utf8'");

$sql = "SELECT * FROM leliki ORDER BY fam";// ORDER BY $sort";
$result = mysqli_query($dbh,$sql);

$count=0;
$i=0;
$counter=0;
$leliks= array(array ());
$row =   array(array());
$itogo = array();
$itogobolt = array();
$itogouklon =array();
$popravki = array();
$unal =array();
//pushing data from DB 2 array
while ($row[$counter] = mysqli_fetch_row($result)) $counter++;
mysqli_free_result($result);



echo "
<form method='GET' action='addcoment.php'>
  <select name='id'>\n";

for ($tmpcnt=0;$tmpcnt<$counter;$tmpcnt++){
  echo "    <option value='";
  echo $row[$tmpcnt][0];
  echo "'>";
  echo $row[$tmpcnt][1];
  echo " ";
  echo $row[$tmpcnt][2];
  echo " ";
  echo $row[$tmpcnt][11];
  echo "</option>\n";
}


echo "  </select>
  <input type='text' size='4' id='korr' name='korr' onfocus=\"if (this.value=='0.0') {this.value='';}\" onblur=\"if (this.value==''){this.value='0.0';}\" value='0.0' onclick='return true;'>
  <input type='text' size='70'id='komm' name='komm' onfocus=\"if (this.value=='комментарий') {this.value='';}\" onblur=\"if (this.value==''){this.value='комментарий';}\" value='комментарий' onclick='return true;'>
<br>  <input type='date' id='datec' name='datec' value='";
echo date("Y-m-d");
echo "'>
  <input type='submit' value='Добавить в базу!'>
</form>
";
?>

</body>
</html>
