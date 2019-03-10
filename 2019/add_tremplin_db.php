<?php
	session_start();
?>
<!doctype html>
<html>
<?php include_once("db.php"); ?>
<head>
<meta charset="utf-8">
<title>Ski'UTC 2018 - Essai Tremplin</title>
<link rel="shortcut icon" href="images/logo.png">
<link rel="stylesheet" href="css/form.css">
</head>
<body>
   <?php
   //  Include PHPExcel_IOFactory
   include 'vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';

   $inputFileName = './tremplin_infos.xls';

   //  Read your Excel workbook
   try {
       $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
       $objReader = PHPExcel_IOFactory::createReader($inputFileType);
       $objPHPExcel = $objReader->load($inputFileName);
   } catch(Exception $e) {
       die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
   }

   //  Get worksheet dimensions
   $sheet = $objPHPExcel->getSheet(0);
   $highestRow = $sheet->getHighestRow();
   // $highestColumn = $sheet->getHighestColumn();
   echo '<p>e'.$highestRow.'</p><br/>';
   // echo '<p>e'.$highestColumn.'</p><br/>';

   //  Loop through each row of the worksheet in turn
   for ($row = 2; $row <= $highestRow; $row++){
      $cell = $sheet->getCellByColumnAndRow(0, $row);
      $price = $cell->getValue();
		$cell = $sheet->getCellByColumnAndRow(1, $row);
      $lastName= $cell->getValue();
		$cell = $sheet->getCellByColumnAndRow(2, $row);
      $firstName = $cell->getValue();
		$cell = $sheet->getCellByColumnAndRow(3, $row);
      $city = $cell->getValue();
		$cell = $sheet->getCellByColumnAndRow(4, $row);
      $mailNotif = $cell->getValue();
		$cell = $sheet->getCellByColumnAndRow(5, $row);
      $mailCo = $cell->getValue();
		if(!$mailCo) $mailCo = $mailNotif;
		$cell = $sheet->getCellByColumnAndRow(6, $row);
      $tel = $cell->getValue();


		// echo $price."\t".$lastName."\t".$firstName."\t".$mailNotif."\t".$mailCo."\t".$city."\t".$tel."<br/>";
		$req = $db->prepare('INSERT INTO users_2019 (type,login,firstName,lastName,email,tel,city,transport_back,payment_first_received,price,caution)
									VALUES (1,:login,:firstName,:lastName,:email,:tel,:city,0,1,:price,0)');
		$req->bindParam(':login', $mailCo, PDO::PARAM_STR, 50);
		$req->bindParam(':firstName', $firstName, PDO::PARAM_STR, 25);
		$req->bindParam(':lastName', $lastName, PDO::PARAM_STR, 25);
		$req->bindParam(':email', $mailNotif, PDO::PARAM_STR, 50);
		$req->bindParam(':tel', $tel, PDO::PARAM_STR, 15);
		$req->bindParam(':city', $city, PDO::PARAM_STR, 50);
		$req->bindParam(':price', $price, PDO::PARAM_INT);
		$req->execute();
		print_r($req->errorInfo());
   }
   ?>
</body>
</html>
