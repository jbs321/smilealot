<?php
/**
 * Model: GKTY_Model_Affiliate_Shortcodes
 * @package GKTY_Model
 */

/**
 * Model class <i>GKTY_Model_Affiliate_Shortcodes</i> represents group
 * @package GKTY_Model
 */

use ApaiIO\ApaiIO;
use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;

class GKTY_Model_Affiliate_Shortcodes {

	/**
	 * Tab table name.
	 */
	const TABLE_NAME = 'gkty_affiliate_shortcodes';

	/**
	 * Handle Shortcodes.
	 */
	public static function handleShortcodes($atts) {
		extract(shortcode_atts(array( "name" => '' , "title" => ''), $atts));

		$shortcode = self::getShortcode($name);
		if (!$shortcode) {
			return '';
		} else {
			if ($shortcode['Mask'] == 1) {
				$shortcode['URL'] = '/?t=rdc&i=' . $shortcode['id'];
			}

            $Nofollow = ($shortcode['Nofollow'] == 1) ? "rel='nofollow'" : "";
			$Follow = ($shortcode['Follow'] == 1) ? "target='_blank'" : "";
			return "<a $Follow $Nofollow href='{$shortcode['URL']}'>$title</a>";
		}
	}

	/**
	 * Create table.
	 */
	public static function createTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		// create list table
		$creation_query =
			'CREATE TABLE ' . self::TABLE_NAME . ' (
			`id` int(20) NOT NULL AUTO_INCREMENT,
			`Shortcode` varchar(255),
			`URL` text,
			`Title` varchar(255),
			`Group` varchar(255),
			`Follow` int(1),
			`Nofollow` int(1),
			`Mask` int(1),
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
		$wpdb->query( $query );
	}


	/**
	 * Get a Shortcode.
	 */
	public static function getShortcode($shortcode) {
		global $wpdb;
		$query = 'select * from ' . self::TABLE_NAME . ' where Shortcode = "' . $shortcode . '";';
		$results = $wpdb->get_results($query, ARRAY_A);

		if(is_array($results))
			return $results[0];
		else
			return false;
	}

	/**
	 * Get a Shortcodes.
	 */
	public static function getShortcodes($return = FALSE) {
		global $wpdb;
		$query = 'select * from ' . self::TABLE_NAME;

        $id = $_POST;

        if(isset($id['id'])){

            $query = 'select * from ' . self::TABLE_NAME . ' WHERE id =' . $id['id'];
            $results = $wpdb->get_row($query, ARRAY_A);
        }else{
            $results = $wpdb->get_results($query, ARRAY_A);
        }

		if(is_array($results))
			if (!$return) {
				echo json_encode($results);
			} else {
				return json_encode($results);
			}
		else
			return false;
	}

	/**
	 * Add new a shortcode.
	 */
	public static function addShortcode( ) {
		global $wpdb;

		$data = $_POST;
		unset($data['action']);

        $exist = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.self::TABLE_NAME.' WHERE Shortcode = %s', $data['Shortcode']));

        if(isset($exist->id)){
            echo json_encode(array('status'=>'error', 'message'=>'Shortcode with the same unique name already exist, please use different shortcode name!'));
            return false;
        }

		$query  = "INSERT INTO " . self::TABLE_NAME;
		$names  = '(';
		$values = '(';
		foreach ( $data as $k => $v ) {
			$v = str_replace( '\'', '\\\'', $v );
			$v = str_replace( '"', '\\"', $v );
			$names .= '`'.$k . '`,';
			$values .= "'$v',";
		}
		$names  = rtrim( $names, ',' ) . ')';
		$values = rtrim( $values, ',' ) . ')';
		$query .= $names . ' VALUES ' . $values;
		try {
			$wpdb->query( $query );
			$index = $wpdb->get_var( 'SELECT LAST_INSERT_ID();' );
		} catch ( Exception $ex ) {
			return $ex->getMessage();
		}
        echo json_encode(array('status'=>'OK', 'message'=>'Successfully Added new shortcode!'));
		return $index;
	}

    /**
     * Add new a shortcode.
     */
    public static function editShortcode( ) {
        global $wpdb;

        $data = $_POST;

        unset($data['action']);

        $what = $data['what'];
        $where = $data['where'];

        $query = "UPDATE ".self::TABLE_NAME." SET ";
        foreach ($what as $k => $v) {
            $v = str_replace('\'', '\\\'', $v);
            $v = str_replace('"', '\\"', $v);
            $query .= "`$k` = '$v',";
        }
        $query = rtrim($query, ',');
        $query .= " WHERE ";
        $c = 0;
        foreach ($where as $k => $v) {
            $c++;
            $AND = ' AND ';
            if ($c == sizeof($where)) $AND = '';
            $query .= "$k = '$v'$AND";
        }
        try {
            $wpdb->query( $query );
            $index = $wpdb->get_var( 'SELECT LAST_INSERT_ID();' );
        } catch (PDOException $ex) {
            return $ex->getMessage();
        }

        echo json_encode(array('status'=>'OK', 'message'=>'Successfully Edited shortcode!'));
        return $index;
    }

	/**
	 * Remove a shortcode.
	 */
	public static function removeShortcode( ) {
		global $wpdb;

		$data = $_POST;
		$id   = $data['id'];

		$wpdb->query( "DELETE FROM " . self::TABLE_NAME . " WHERE ID = '$id'" );
	}

}
