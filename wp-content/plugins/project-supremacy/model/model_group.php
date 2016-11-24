<?php
/**
 * Model: GKTY_Model_Group
 * @package GKTY_Model
 */

/**
 * Model class <i>GKTY_Model_Group</i> represents group
 * @package GKTY_Model
 */

class GKTY_Model_Group {
  /**
   * Tab table name.
   */
  const TABLE_NAME = 'gkty_group';

  /**
   * Holds found group count.
   */
  static $foundCount;

  /**
   * Holds all group count.
   */
  static $all_count;

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
			`title` varchar(255),
			`url` varchar(255),
			`description` varchar(1024),
			`h1` varchar(255),
			`project_id` int(20),
			`id_post_page` int(20),
			`enabled_seo` int(1) DEFAULT "1",
			PRIMARY KEY (`id`)
			) ' .$charset_collate. ';';

	  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  dbDelta( $creation_query );
  }

  /**
   * Remove table
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
	 * Get data by id
	 * @param $id
	 */
	public static function getDataByPostID($id) {
		global $wpdb;
		$query = 'select * from ' . self::TABLE_NAME . ' where id_post_page=' . $id . ';';
		$results = $wpdb->get_results($query, ARRAY_A);

		if(is_array($results))
			return $results[0];
		else
			return false;
	}

  /**
   * Get rows by project_id.
   * @param int $project_id
   * @return array
   */
  public static function getRowsByProject($project_id) {
    global $wpdb;
    $query = 'select * from ' . self::TABLE_NAME . ' where project_id=' . $project_id . ';';
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
    $query .= '(name,project_id) ';
    $query .= "VALUES ('{$data['name']}','{$data['project_id']}');";
    $wpdb->query($query);
    $index = $wpdb->get_var('SELECT LAST_INSERT_ID();');
    return $index;
  }

  /**
   * Remove Group.
   */
  public static function removeRecord($id) {
    global $wpdb;
    $query = 'delete from ' . self::TABLE_NAME . ' ';
    $query .= 'where id=' . $id . ';';
    $wpdb->query($query);
    GKTY_Model_Keyword::removeRecordByGroupID($id);
  }

    /**
     * Remove Empty Groups by project_id.
     */
    public static function removeEmptyRecordByProjectID($project_id)
    {
        global $wpdb;

        // Delete keywords related this project id.
        $query = 'SELECT * FROM ' . self::TABLE_NAME . ' ';
        $query .= 'where project_id=' . $project_id . ' and name = "" and title IS NULL;';
        $groups = $wpdb->get_results($query, ARRAY_A);
        if (is_array($groups)) {
            foreach ($groups as $group) {
                GKTY_Model_Keyword::removeRecordByGroupID($group['id']);
            }
        }

        // Delete groups by project id.
        $query = 'delete from ' . self::TABLE_NAME . ' ';
        $query .= 'where project_id=' . $project_id . ' and name = "" and title IS NULL;;';
        $wpdb->query($query);
    }

        /**
   * Remove Group by project_id.
   */
  public static function removeRecordByProjectID($project_id) {
    global $wpdb;

    // Delete keywords related this project id.
    $query = 'SELECT * FROM ' . self::TABLE_NAME . ' ';
    $query .= 'where project_id=' . $project_id . ';';
    $groups = $wpdb->get_results($query, ARRAY_A);
    if (is_array($groups)) {
      foreach ($groups as $group) {
        GKTY_Model_Keyword::removeRecordByGroupID($group['id']);
      }
    }

    // Delete groups by project id.
    $query = 'delete from ' . self::TABLE_NAME . ' ';
    $query .= 'where project_id=' . $project_id . ';';
    $wpdb->query($query);
  }

  /**
   * Get all Products by pagenum, per_page.
   */
  public static function getAll($project_id, $orderby, $order, $pagenum, $per_page) {
    global $wpdb;

    $limit = ($pagenum - 1) * $per_page;
    $query = 'SELECT * FROM ' . self::TABLE_NAME . ' ';
    $query .= 'WHERE project_id=' . $project_id . ' ';
    $query .= 'ORDER BY ' . $orderby . ' ' . $order . ' ';
    $query .= 'LIMIT ' . $limit . ',' . $per_page . ' ;';

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
