<h2>Редактирование колонки: <?= htmlspecialchars($field_item['Field']); ?></h2>

   <form action = "myadmin.php" method = 'POST'>
<p><input type ='text' name='field' size = '25' value ='<?= htmlspecialchars($field_item['Field']); ?>' > <input type ='text' name='type' size = '25' value ='<?= htmlspecialchars($field_item['Type']); ?>' > 
		<input type ='hidden' name='id' value ='<?= htmlspecialchars($field_item['Field']); ?>' >
		<input type ='hidden' name='table' value ='<?= htmlspecialchars($table); ?>' >
		<br/>
		<br/>
		<input type="submit" name="change" value="Cозранить изменения" /></p>
	</form>