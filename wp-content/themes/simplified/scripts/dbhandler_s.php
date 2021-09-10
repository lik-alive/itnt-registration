<?php
//-----General DB functions

//Get the last error from DB
function db_get_last_error()
{
	global $wpdb;
	return $wpdb->last_error;
}

//-----Table DB functions

//Add entity to the specified table
function db_add_TH($tablename, $entity)
{
	//Convert object to array
	if (is_object($entity)) $entity = get_object_vars($entity);

	global $wpdb;
	$result = $wpdb->insert($tablename, $entity);
	$id = $wpdb->insert_id;

	if (false === $result) throw new DataException("Ошибка добавления записи", array('function' => __FUNCTION__, 'details' => db_get_last_error(), 'table' => $tablename, 'value' => $entity));
	else g_ldv("Добавлена запись", __FUNCTION__, $tablename, $id, $entity);

	return $id;
}

//Update entity from the specified table by id
function db_set_TH($tablename, $entity)
{
	//Convert object to array
	if (is_object($entity)) $entity = get_object_vars($entity);

	$id = $entity['ID'];

	global $wpdb;
	$old = db_get($tablename, $id);
	$result = $wpdb->update($tablename, $entity, array('ID' => $id));

	if (false === $result) throw new DataException("Ошибка обновления записи", array('function' => __FUNCTION__, 'details' => db_get_last_error(), 'table' => $tablename, 'id' => $id, 'value' => array('new' => $entity, 'old' => $old)));
	else if (0 !== $result) g_ldv("Обновлена запись", __FUNCTION__, $tablename, $id, array('new' => $entity, 'old' => $old));

	return $result;
}

//Delete entity from the specified table by id
function db_remove_TH($tablename, $id)
{
	global $wpdb;
	$old = db_get($tablename, $id);
	$result = $wpdb->delete($tablename, array('ID' => $id));

	if (false === $result) throw new DataException("Ошибка удаления записи", array('function' => __FUNCTION__, 'details' => db_get_last_error(), 'table' => $tablename, 'id' => $id, 'value' => $old));
	else g_ldv("Удалена запись", __FUNCTION__, $tablename, $id, $old);

	return $result;
}

//Get all entities from the specified table
function db_list($tablename)
{
	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT *
		FROM $tablename"
	);

	return $result;
}

// Get all entities on conditions
function db_list_cond($tablename, $where = '1', $orderby = '1')
{
	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT *
		FROM $tablename
		WHERE $where
		ORDER BY $orderby"
	);

	return $result;
}

//NOTE unsafe tablename, idname
//Get single entity from the specified table by id
function db_get($tablename, $id, $output = 'OBJECT')
{
	$id = (int) $id;
	global $wpdb;
	$result =  $wpdb->get_row(
		"SELECT *
		FROM $tablename t
		WHERE t.ID = $id",
		$output
	);

	return $result;
}

//NOTE unsafe tablename, fieldname, idname
//Get single field from the specified table by id and fieldname
function db_get_field($tablename, $fieldname, $id)
{
	$id = (int) $id;
	global $wpdb;
	$result =  $wpdb->get_var(
		"SELECT t.$fieldname
		FROM $tablename t
		WHERE t.ID = $id"
	);

	return $result;
}

//NOTE unsafe tablename, idname
//Update single field from the specified table by id
function db_set_field_TH($tablename, $fieldname, $value, $id)
{
	$entity = array($fieldname => $value);
	global $wpdb;
	$old = db_get($tablename, $id, 'ARRAY_A')[$fieldname];
	$result = $wpdb->update($tablename, $entity, array('ID' => $id));

	if (false === $result) throw new DataException("Ошибка обновления поля", array('function' => __FUNCTION__, 'details' => db_get_last_error(), 'table' => $tablename, 'id' => $id, 'value' => array('new' => $entity, 'old' => $old)));
	else if (0 !== $result) g_ldv("Обновлено поле", __FUNCTION__, $tablename, $id, array('new' => $entity, 'old' => $old));

	return $result;
}
