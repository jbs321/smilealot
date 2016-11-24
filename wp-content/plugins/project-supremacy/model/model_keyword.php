<?php
/**
 * Model: GKTY_Model_Keyword
 * @package GKTY_Model
 */

/**
 * Model class <i>GKTY_Model_Keyword</i> represents keyword
 * @package GKTY_Model
 */

class GKTY_Model_Keyword {

  /**
   * Tab table name.
   */
  const TABLE_NAME = 'gkty_keyword';

  /**
   * Holds found keyword count.
   */
  static $foundCount;

  /**
   * Holds all keyword count.
   */
  static $allCount;

  /**
   * Create table.
   */
  public static function createTable() {
	  global $wpdb;
	  $charset_collate = $wpdb->get_charset_collate();

	  $creation_query =
      'CREATE TABLE ' . self::TABLE_NAME . ' (
			`id` int(20) NOT NULL AUTO_INCREMENT,
			`name` varchar(255),
			`volumn` int(20),
			`cpc` varchar(255),
			`inbroad` int(20),
			`intitle` int(20),
			`inparse` int(20),
			`inurl` int(20),
			`group_id` int(20),
			`scraped` int(2),
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
   * Get data by id.
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
   * Get rows by project_id.
   * @param int $group_id
   * @return array
   */
  public static function getRowsByGroup($group_id) {
    global $wpdb;
    $query = 'select * from ' . self::TABLE_NAME . ' where group_id=' . $group_id . ';';
    $results = $wpdb->get_results($query, ARRAY_A);

    if(!is_array($results))
      return array();
    else
      return $results;
  }

  /**
   * Add Product.
   */
  public static function addRecord($data) {
    global $wpdb;
    $query = 'INSERT INTO ' .  self::TABLE_NAME  . ' ';
    $query .= '(name,volumn,cpc,inbroad,intitle,inparse,inurl,group_id,scraped) ';
    $query .= "VALUES ('{$data['name']}','{$data['volumn']}','{$data['cpc']}','{$data['inbroad']}','{$data['intitle']}','{$data['inparse']}','{$data['inurl']}','{$data['group_id']}','0');";
    $wpdb->query($query);
    $index = $wpdb->get_var('SELECT LAST_INSERT_ID();');
    return $index;
  }

	/**
	 * Remove Products.
	 */
	public static function removeRecords($group_id,$ids) {
		global $wpdb;
		$query = 'delete from ' . self::TABLE_NAME . ' ';
		$query .= 'where id in (' . $ids . ');';
		$wpdb->query($query);
		return $query;
	}


  /**
   * Remove Product.
   */
  public static function removeRecord($id) {
    global $wpdb;
    $query = 'delete from ' . self::TABLE_NAME . ' ';
    $query .= 'where id=' . $id . ';';
    $wpdb->query($query);
  }

  /**
   * Remove Group by group_id.
   */
  public static function removeRecordByGroupID($group_id) {
    global $wpdb;
    $query = 'delete from ' . self::TABLE_NAME . ' ';
    $query .= 'where group_id=' . $group_id . ';';
    $wpdb->query($query);
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
    self::$foundCount =  self::get_count_element();

    if(!is_array($results)) {
      $results = array();
    }
    return $results;
  }

  /**
   * Get all Products by pagenum, per_page.
   */
  public static function getAllRows() {
    global $wpdb;
    $query = 'SELECT * FROM ' . self::TABLE_NAME . ' ';
    $results = $wpdb->get_results($query, ARRAY_A);
    if(!is_array($results)) {
      $results = array();
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
	public static function updateElement($id, $data, $scraped = 0) {
		global $wpdb;
		$data['scraped'] = $scraped;
		$query = "UPDATE " . self::TABLE_NAME . " SET ";
		foreach ($data as $k => $v) {
			$v = str_replace('\'', '\\\'', $v);
			$v = str_replace('"', '\\"', $v);
			if (is_numeric($v)) {
				$query .= "$k = $v,";
			} else {
				$query .= "$k = '$v',";
			}
		}
		$query = rtrim($query, ',');
		$query .= " WHERE id = " . $id . ";";
		$wpdb->query($query);
		return true;
	}
}
