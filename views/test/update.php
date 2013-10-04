<form method="post">
	<label>ำรปงร๛</label><input type="text" name="name" value="<?php echo $response->user['name'];?>"/><br/>
	<label>รÜย๋</label><input type="text" name="pass" value="<?php echo $response->user['pass'];?>"/><br/>
	<input type="submit" name="submit" value="save"/>
</form>