<?php
	
	require("shared/connect.php");
	
	if(isset($_POST['name']) && isset($_POST['login']) && isset($_POST['room']) && isset($_POST['year']) && isset($_POST['department']) && isset($_POST['gender'])) {
		require('secret/sqlite.php');
		connect_db('secret/insannu.db');
		
		// Creates the SQL request:
		$sql_query = '';
		$params = array();
		$equivalence = '';
		$order_by = '';
		if($_POST['name']!='') {
			$_POST['name'] = str_replace(array('é', 'è', 'à', 'ë', 'ê', 'ï', 'î'), array('e', 'e', 'a', 'e', 'e', 'i', 'i'), $_POST['name']);
			$names = explode(' ', $_POST['name']);
			foreach($names as $name) {
				$sql_query .= " AND (first_name LIKE ? OR last_name LIKE ?)";
				array_push($params, "%".$name."%", "%".$name."%"); 
			}
			$equivalence .= $_POST['name'].'+';
		}
		if($_POST['login']!='') {
			$sql_query .= " AND login LIKE ?";
			$params[] = "%".$_POST['login']."%";
			$equivalence .= $_POST['login'].'+';
		}
		if($_POST['room']!='') {
			if(substr($_POST['room'], 0, 2)=='bn') {
				$bnc = substr($_POST['room'], 0, 2).'c'.substr($_POST['room'], 2);
				$bns = substr($_POST['room'], 0, 2).'s'.substr($_POST['room'], 2);
				$bnn = substr($_POST['room'], 0, 2).'n'.substr($_POST['room'], 2);
				$sql_query = " AND (room LIKE ? OR room LIKE ? OR room LIKE ?)";
				array_push($params, "%".$bnc."%", "%".$bns."%", "%".$bnn."%");
			} else {
				$sql_query .= " AND room LIKE ?";
				$params[] = "%".$_POST['room']."%";
			}
			$equivalence .= $_POST['room'].'+';
			$order_by = "room, ";
		}
		if($_POST['gender']!='') {
			$sql_query .= " AND gender LIKE ?";
			$params[] = $_POST['gender'];
			$equivalence .= ($_POST['gender']=='male')? 'gars+' : 'fille+';
		}
		if($_POST['year']!='' && $_POST['department']!='' && isset($_POST['groupe']) && $_POST['groupe']!='') {
			$sql_query .= "AND year = ? AND department = ? AND groupe = ?";
			array_push($params, $_POST['year'], $_POST['department'], $_POST['groupe']);
			if($_POST['department']=='STPI') {
				$equivalence .= $_POST['year'].$_POST['groupe'].'+';
			} else {
				$equivalence .= $_POST['year'].$_POST['groupe'].'+'.$_POST['department'].'+';
			}
		} elseif($_POST['year']!='' && isset($_POST['groupe']) && $_POST['groupe']!='') {
			$sql_query .= "AND year = ? AND groupe = ?";
			array_push($params, $_POST['year'], $_POST['groupe']);
			$equivalence .= $_POST['year'].$_POST['groupe'].'+';
		} elseif($_POST['department']!='' && isset($_POST['groupe']) && $_POST['groupe']!='') {
			$sql_query .= "AND department = ? AND groupe = ?";
			array_push($params, $_POST['department'], $_POST['groupe']);
			if($_POST['department']=='STPI') {
				$equivalence .= 'STPI-'.$_POST['groupe'].'+';
			} else {
				$equivalence = '';
			}
		} elseif($_POST['year']!='' && $_POST['department']!='') {
			$sql_query .= " AND year = ? AND department = ?";
			array_push($params, $_POST['year'], $_POST['department']);
			$equivalence .= $_POST['year'].$_POST['department'].'+';
		} elseif($_POST['year']!='') {
			$sql_query .= " AND year = ?";
			$params[] = $_POST['year'];
			$equivalence .= $_POST['year'].'AN+';
		} elseif($_POST['department']!='') {
			$sql_query .= " AND department = ?";
			$params[] = $_POST['department'];
			$equivalence .= $_POST['department'].'+';
		}
		$sql_query = ltrim($sql_query, ' AND'); // Deletes the first AND.
		
		// Executes the request on the database:
		if($sql_query!='') {
			try {
				$query = $GLOBALS['bdd']->prepare("SELECT * FROM students WHERE ".$sql_query." ORDER BY ".$order_by."last_name, first_name, student_id;");    
				$query->execute($params);
			} catch (Exception $e) {
				exit('Error : '.$e->getMessage());
			}
		}
	}
	
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Insannu</title>
    <link rel="shortcut icon" href="img/star.gif" />
    <link href="/css/default.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">
		function verifGroupe() {
			var field_groupe = document.getElementById('field_groupe');
			var year = document.getElementById('year').value;
			if(year==1 || year==2 || document.getElementById('department').value=='STPI') {
				field_groupe.setAttribute('style', 'display:inline;');
			} else {
				field_groupe.setAttribute('style', 'display:none;');
			}
		}
	</script>
</head>
    
<body>

    <?php include("shared/header.php"); ?>

	<form method="post" action="advanced_search.php">
		<p id="advanced_search">
			<label for="name">Nom:</label>
			<input type="text" name="name" id="name"/><br/>
			<label for="login">Login ENT:</label>
			<input type="text" name="login" id="login"/><br/>
			<label for="room">Chambre:</label>
			<input type="text" name="room" id="room"/><br/>
			<label for="year">Année:</label>
			<select name="year" id="year" onchange="verifGroupe();">
				<option value="" selected="selected">Tous</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select><br/>
			<label for="department">Département:</label>
			<select name="department" id="department" onchange="verifGroupe();">
				<option value="" selected="selected">Tous</option>
				<option value="STPI">STPI</option>
				<option value="INFO">INFO</option>
				<option value="GCU">GCU</option>
				<option value="GMA">GMA</option>
				<option value="EII">EII</option>
				<option value="SGM">SGM</option>
				<option value="SRC">SRC</option>
			</select><br/>
			<span id="field_groupe" style="display:none;">
				<label for="groupe">Groupe:</label>
				<select name="groupe" id="groupe">
					<option value="">Tous</option>
					<option value="A">A</option>
					<option value="B">B</option>
					<option value="C">C</option>
					<option value="D">D</option>
					<option value="E">E</option>
					<option value="F">F</option>
					<option value="G">G</option>
					<option value="H">H</option>
					<option value="I">I</option>
					<option value="J">J</option>
					<option value="K">K</option>
				</select><br/>
			</span>
			<label for="gender">Tous:</label>
			<input type="radio" name="gender" id="gender" value="" checked="checked"/><br/>
			<label for="gender">Homme:</label>
			<input type="radio" name="gender" id="gender" value="male"/><br/>
			<label for="gender">Femme:</label>
			<input type="radio" name="gender" id="gender" value="female"/><br/>
			<input type="submit" value="Rechercher"/>
		</p>
	</form>
	<br/><br/>

	<?php
		if(isset($query)) {
			if($equivalence!='') echo '<a href="index.php?search='.$equivalence.'">Recherche instantanée équivalente.</a><br/>';
		
			echo '<ul id="results">';
			while($data = $query->fetch()) {
				echo '<li id="'.$data['student_id'].'">';
				if($data['picture']==1) {
					echo '<img height="192" width="144" src="photos/'.$data['student_id'].'.jpg" alt="'.$data['first_name'].' '.$data['last_name'].'" title="'.$data['first_name'].' '.$data['last_name'].'"/>';
				} elseif($data['gender']=='Female') {
					echo '<img height="192" width="144" src="photos/default_female.jpg" alt="'.$data['first_name'].' '.$data['last_name'].'" title="'.$data['first_name'].' '.$data['last_name'].'"/>';
				} else {
					echo '<img height="192" width="144" src="photos/default_male.jpg" alt="'.$data['first_name'].' '.$data['last_name'].'" title="'.$data['first_name'].' '.$data['last_name'].'"/>';
				}
				echo $data['first_name'].' '.ucwords($data['last_name']).'<br/>';
				if($data['room']=='Externe') {
					echo $data['room'].'<br/>';
				} else if($data['room']=='') {
					echo 'Chambre inconnue<br/>';
				} else {
					$couloir = (substr($data['room'], 0, 2)=='BN')? substr($data['room'], 0, 4) : substr($data['room'], 0, 3);
					echo '<a href="index.php?search='.$couloir.'">'.$data['room'].'</a><br/>';
				}
				if($data['groupe']!='') {
					echo '<a href="index.php?search='.$data['year'].$data['groupe'].'">'.$data['year'].$data['department'].'-'.$data['groupe'].'</a>';
				} else {
					if($data['department']!='Doctorant' && $data['department']!='Master') echo $data['year'];
					echo $data['department'];
				}
				echo '</li>';
			}
			$query->closeCursor();
			echo '</ul><br/><br/>';
		}
	?>
	
	<?php include("shared/footer.php"); ?>

</body>
</html>