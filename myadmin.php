<?php
header("Content-Type: text/html; charset=utf-8");

try{
	$pdo = new PDO('mysql:host=localhost;dbname=trade', 'root', '');
}
catch (PDOException $e){
	
	echo "Невозможно подключиться к Базе данных";
	
}


$sql = "CREATE TABLE `product` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(50) NOT NULL,
		`brand` varchar(50) NOT NULL,
		`description` varchar(255) NOT NULL,
		`price` float NOT NULL,
		`code` int(50) NOT NULL,
		`category_id` int(11) NULL,
		`is_available` tinyint(4) NOT NULL,
		PRIMARY KEY (`id`)

		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		
$stmt = $pdo->prepare($sql);
$stmt->execute();	

$sql = "CREATE TABLE `category` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`category_name` varchar(50) NOT NULL,
		 PRIMARY KEY (`id`)

		) ENGINE=InnoDB DEFAULT CHARSET=utf8";	
		
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Редактирование колонок выбранной таблицы
if($_POST['change']){
	
	$table_id = trim(addslashes($_POST['table']));
	$field_edited = trim(addslashes($_POST['field']));
	$type = trim(addslashes($_POST['type']));
	$field_name = $_POST['id'];
	
$stmt = $pdo->prepare("ALTER TABLE {$table_id} CHANGE {$field_name} {$field_edited} {$type}");
$stmt->execute();
header('Location: myadmin.php?table='.$table_id);
	
}
	

// Показ списка таблиц
$tables= [];
	$stmt = $pdo->query("SHOW TABLES");
	$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Описание выбранной таблицы	
if($_GET['table']){
$table = trim(addslashes($_GET['table']));	
$stmt = $pdo->prepare("DESCRIBE {$table}");
$stmt->execute();
$table_info = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Удаление колонок
if($_GET['action']=='delete'){
	
$field = trim(addslashes($_GET['field']));
$stmt = $pdo->prepare("ALTER TABLE {$table} DROP COLUMN {$field}");
$stmt->execute();
header('Location: myadmin.php?table='.$table);
}

if($_GET['action']=='edit'){
	$field_id = trim(addslashes($_GET['field']));
	foreach($table_info as $field){
		if($field['Field']== $field_id ){
			$field_item = $field;
		}
	}
	
	ob_start();
	
	include_once 'view_form.php';
	
	$view_form = ob_get_contents();
	
	ob_end_clean();
	
	}
}	

	
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Вывод таблиц из базы данных</title>
    <style>
     ul, table, td, input, th { border: 1px solid black; border-collapse: collapse; list-style: none;font-size: 20px;}
	 
    </style>
  </head>
  <body>
 
<div>
<div style="width: 50%; margin: auto;">
    <h2>Таблицы базы данных</h2>

	<ul>
	
	<?php foreach($tables as $tab): ?>
		<?php foreach($tab as $key => $value): ?>
	<li><a href="?table=<?= $value; ?>">Выбрать <?= $value; ?></a></li>
		<?php endforeach; ?>
    <?php endforeach; ?>
	</ul>


<?php if(isset($table)): ?>
<div style="width: 70%; margin: auto">
<h2>Таблица: <?= $table; ?></h2>

<table style="width: 100%;">
		<tr><th>Колонка</th><th>Тип</th><th colspan="2">Операции</th></tr>
		
		<?php foreach($table_info as $fields): ?>
	<tr>
		<td><?= $fields['Field'];?></td>
		<td><?= $fields['Type'];?></td>
	
		 <td><a href='?table=<?= $table; ?>&field=<?= $fields['Field']; ?>&action=delete'>Удалить</a></td>
		 <td><a href='?table=<?= $table; ?>&field=<?= $fields['Field']; ?>&action=edit'>Изменить</a></td>
	</tr>
		<?php endforeach; ?>
		
</table>
<?= $view_form; ?>
</div>
<?php endif; ?>	
</div>
</div>	


  </body>
</html>