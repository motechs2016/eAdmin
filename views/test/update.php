<form method="post">
	<label>�û���</label><input type="text" name="name" value="<?php echo $response->user['name'];?>"/><br/>
	<label>����</label><input type="text" name="pass" value="<?php echo $response->user['pass'];?>"/><br/>
	<input type="submit" name="submit" value="save"/>
</form>