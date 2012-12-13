<?php 

echo json_encode(array('mode'=> 'full', 'data' => array(array('le_id' => '1', 'event' => 'on_register_user', 'action' => 'add', 'group_id' => 2, 'list_id' => 2),
	array('le_id' => '2', 'event' => 'on_register_user', 'action' => 'remove', 'group_id' => 2, 'list_id' => 2),
	array('le_id' => '3', 'event' => 'on_register_user', 'action' => 'add', 'group_id' => 3, 'list_id' => 2)
)));

die;

?>