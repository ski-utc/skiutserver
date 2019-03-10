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

   $inputFileName = './tremplin_options.xls';

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
      $option = $cell->getValue();
		$cell = $sheet->getCellByColumnAndRow(2, $row);
      $login = $cell->getValue();

		switch($option){
			case 'Food pack':
				$req = $db->prepare('UPDATE users_2019
											SET food=1
											WHERE login=:login');
				$req->bindParam(':login', $login);
				$req->execute();
				break;

			case 'Pack ski Bronze':
				$req = $db->prepare('UPDATE users_2019
											SET equipment=1, pack=1, items=3
											WHERE login=:login');
				$req->bindParam(':login', $login);
				$req->execute();
				break;

			case 'Pack ski Argent':
				$req = $db->prepare('UPDATE users_2019
											SET equipment=1, pack=2, items=3
											WHERE login=:login');
				$req->bindParam(':login', $login);
				$req->execute();
				break;

			case 'Pack ski Or':
				$req = $db->prepare('UPDATE users_2019
											SET equipment=1, pack=3, items=3
											WHERE login=:login');
				$req->bindParam(':login', $login);
				$req->execute();
				break;

			case 'Assurance annulation et bagage':
				$req = $db->prepare('UPDATE users_2019
											SET assurance_annulation=1
											WHERE login=:login');
				$req->bindParam(':login', $login);
				$req->execute();
				break;

			case 'Assurance rapatriement':
				$req = $db->prepare('UPDATE users_2019
											SET assurance_rapa=1
											WHERE login=:login');
				$req->bindParam(':login', $login);
				$req->execute();
				break;

			default:
				echo "tu as oublié l'option ".$option."<br/>";
		}
		print_r($req->errorInfo());
   }
   ?>
</body>
</html>
