<li><select name="<?=$_GET['n']?>">
<?php

	require_once( 'dbdriver.php' );
	
	$db = new dbdriver();
	if( $db->read_bs() )
	{
		while( $row = $db->next_bs() )
		{
			echo '<option value="'.$row['id'].'">' . $row['nazwa'] . '</option>';
		}
	}
	$db->free_result();
	$db->release();

?>
</select></li>