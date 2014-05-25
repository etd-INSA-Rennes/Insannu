<?php

require_once('../../include/installation/Installation.class.php');
set_time_limit(0);

?>
<html>
<head>
	<meta charset="utf-8"/>
	<title>Installation of the Insannu</title>
</head>
<body>
	<?php

		if(isset($_POST['process'])) {
			if(Installation::getInstance()->process()) {
				Installation::getInstance()->displayResult();
				Installation::getInstance()->goToNextStep();
			} else {
				echo 'Une erreur est survenue ! (et tu vas devoir te débrouiller pour découvrir ce qui se passe...)';
			}
		} else {
			Installation::getInstance()->display();
		}

	?>
</body>
</html>