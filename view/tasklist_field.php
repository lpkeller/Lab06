<?php foreach( $task_list as $task ) : ?>
	<input type="hidden" name="tasklist[]" value="<?php echo $task; ?>">
<?php endforeach; ?>