<form action="insert_acl_action" method="post">
	<table border="1" cellspacing="0">
		<tr><th>parent_id</th>
			<td><select name="parent_id">
				<option value="0">0</option>
				<?php foreach($response->action_list as $list):?>
					<option value="<?php echo $list['id'];?>">
						<?php echo $list['action_name'];?>
					</option>
				<?php endforeach;?>
				</select>
			</td>
		</tr>
		<tr>
			<th>type</th>
			<td><input type="radio" name="type" value="cote" checked="checked"/>cote
				<input type="radio" name="type" value="group"/>group
				<input type="radio" name="type" value="url"/>url
			<td>
		</tr>
		<tr>
			<th>action_name:</th>
			<td><Input type="text" name="action_name"/></td>
		</tr>
		<tr>
			<th>action_code:</th>
			<td><Input type="text" name="action_code"/></td>
		</tr>
		<tr>
			<th>sort_by:</th>
			<td><input type="text" name="sort_by"/></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" name="submit"/></td>
		</tr>
	</table>
</form>
