<!DOCTYPE html>
<html>
	<head>
		<title></title>
	</head>
	<body>

		<?php 
			echo '<a href="https://cas.utc.fr/cas/serviceValidate?service=http://assos.utc.fr/skiutc/CAS/get.php&ticket='.$_GET['ticket'].'">';
			echo 'Accéder au XML du ticket';
			echo '</a>';
		?>

	</body>
</html>