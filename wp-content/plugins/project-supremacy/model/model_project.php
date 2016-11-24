<?php
/**
 * Model: GKTY_Model_Project
 * @package GKTY_Model
 */

/**
 * Model class <i>GKTY_Model_Project</i> represents project
 * @package GKTY_Model
 */

class GKTY_Model_Project {
  /**
   * Tab table name.
   */
  const TABLE_NAME = 'gkty_project';

  /**
   * Holds found campaign count.
   */
  static $foundCount;

  /**
   * Holds all campaign count.
   */
  static $allCount;

  /**
   * Create Table.
   */
  public static function createTable() {
	  global $wpdb;
	  $charset_collate = $wpdb->get_charset_collate();

	  $creation_query =
      'CREATE TABLE ' . self::TABLE_NAME . ' (
			`id` int(20) NOT NULL AUTO_INCREMENT,
			`name` varchar(255),
			`created_date` varchar(20),
			PRIMARY KEY (`id`)
			) ' .$charset_collate. ';';

	  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  dbDelta( $creation_query );
  }

  /**
   * Remove table.
   */
  public static function removeTable() {
    global $wpdb;
    $query = 'DROP TABLE IF EXISTS ' . self::TABLE_NAME . ';';
    $wpdb->query($query);
  }

  /**
   * Get data by id
   * @param $id
   */
  public static function getData($id) {
    global $wpdb;
    $query = 'select * from ' . self::TABLE_NAME . ' where id=' . $id . ';';
    $results = $wpdb->get_results($query, ARRAY_A);

    if(is_array($results))
      return $results[0];
    else
      return false;
  }

  /**
   * Add Product.
   */
  public static function addRecord($data) {
    global $wpdb;

    $query = 'INSERT INTO ' .  self::TABLE_NAME  . ' ';
    $query .= '(name,created_date) ';
    $query .= "VALUES ('{$data['name']}','{$data['created_date']}');";

    $wpdb->query( $query );

    $index = $wpdb->get_var('SELECT LAST_INSERT_ID();');

    return $index;

  }

  /**
   * Remove Product.
   */
  public static function removeRecord($id) {
    global $wpdb;
    $query = 'delete from ' . self::TABLE_NAME . ' ';
    $query .= 'where id=' . $id . ';';
    $wpdb->query($query);
    GKTY_Model_Group::removeRecordByProjectID($id);
  }

  /**
   * Get all Products by pagenum, per_page.
   */
  public static function getAll($orderby, $order, $pagenum, $per_page) {
    global $wpdb;

    $limit = ($pagenum - 1) * $per_page;
    $query = 'SELECT * FROM ' . self::TABLE_NAME . ' ';
    $query .= 'ORDER BY ' . $orderby . ' ' . $order . ' ';
    $query .= 'LIMIT ' . $limit . ',' . $per_page . ';';

    $results = $wpdb->get_results($query, ARRAY_A);
    self::$foundCount =  self::getCountElement();

    if(!is_array($results)) {
      $results = array();
      return $results;
    }

    return $results;
  }

  /**
   * Get count of row.
   */
  public static function getCountElement() {
    global $wpdb;

    $query = 'Select count(*) from ' . self::TABLE_NAME . ';';

    $count = $wpdb->get_var($query);

    return $count;
  }

  /**
   * Update record.
   */
  public static function updateElement($id, $data) {
    global $wpdb;

    $query = "update " . self::TABLE_NAME . " ";
    $query .= "set name='{$data['name']}' ";
    $query .= "where id=" . $id . ";";

    $wpdb->query($query);

    return true;
  }
}
