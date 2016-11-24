<?php
/**
 * Model: GKTY_Model_Articles
 * @package GKTY_Model
 */

/**
 * Model class <i>GKTY_Model_Articles</i> represents group
 * @package GKTY_Model
 */

use ApaiIO\ApaiIO;
use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;

class GKTY_Model_Articles {
	/**
	 * Tab table name.
	 */
	const TABLE_NAME = 'gkty_articles';

	/**
	 * Create table.
	 */
	public static function createTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$creation_query =
			'CREATE TABLE ' . self::TABLE_NAME . ' (
			`article` int(20) NOT NULL,
			`batch` varchar(255),
			`cost` varchar(255),
			`keywords` varchar(255),
			`status` varchar(20),
			`title` varchar(255),
			`body` longtext,
			`spun` longtext,
			PRIMARY KEY (`article`)
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

	public static function submit_iNeedArticles() {


        self::createTable();

		$iNeedArticles_URL = 'http://ineedarticles.com/api.php';
		$iNeedArticles_API = get_option('iNeedArticles_Key');
		if (!$iNeedArticles_API) {
            wp_send_json(array("status"=>"ERROR" , "message"=>"API key is not currently set for iNeedArticles!"));
		}

		// get the post var
		$p = $_POST;
		$p['action'] = "requestArticles";

		// set username and api
		$p['apikey'] = $iNeedArticles_API;

		// make query
		$URL =  $iNeedArticles_URL;

		//print data
		$json = GKTY_Page_Project::makeRequest($URL, array('proxy'=>FALSE, 'method'=>'POST', 'data'=>http_build_query($p)));
		$json = json_decode($json, TRUE);

		if (!$json) {
            wp_send_json(array("status"=>"ERROR" , "message"=>"Invalid JSON format, please contact support!"));
		} else {
            if ($json['success'] == 1) {
                $json_batch = GKTY_Page_Project::makeRequest($URL, array('proxy'=>FALSE, 'method'=>'POST', 'data'=>http_build_query(array('action'=>'batchArticles', 'apikey'=>$iNeedArticles_API, 'batch'=>$json['output']['batch']))));
                $json_batch = json_decode($json_batch, true);
                if ((sizeof($json_batch['output']) > 0) && ($json_batch['success'] == 1)) {
                    global $wpdb;
	                foreach($json_batch['output'] as $data) {
		                $query = 'INSERT INTO ' .  self::TABLE_NAME  . ' ';
		                $query .= '(batch,cost,article,keywords,status) ';
		                $query .= "VALUES ('{$json['output']['batch']}','{$json['output']['cost']}','{$data['article']}','{$data['keywords']}','{$data['status']}');";

		                $wpdb->query( $query );
	                }
                    wp_send_json(array("status"=>"OK" , "message"=>"Article(s) successfully requested!"));
                }else{
					wp_send_json(array("status"=>"ERROR" , "message"=>"Invalid Response. Please contact support."));
				}

            }else{
                wp_send_json(array("status"=>"ERROR" , "message"=>$json['output'][0]));
            }
		}
		return null;
	}

	public static function datatable_iNeedArticles() {
		global $wpdb;

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * Easy set variables
		 */

		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */
		$aColumns = array(
			'article',
			'batch',
			'cost',
			'keywords',
			'status',
			'title',
			'body',
			'spun'
		);

		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "article";

		/* DB table to use */
		$sTable = self::TABLE_NAME;

		/*
		 * Paging
		 */
		$sLimit = '';
		if ( isset( $_POST['iDisplayStart'] ) && $_POST['iDisplayLength'] != '-1' ) {
			$sLimit = "LIMIT " . ( $_POST['iDisplayStart'] ) . ", " .
				( $_POST['iDisplayLength'] );
		} else {
			$sLimit = "LIMIT 0,50";
		}


		/*
		 * Ordering
		 */
		$sOrder = '';
		if ( isset( $_POST['iSortCol_0'] ) ) {
			$sOrder = "ORDER BY  ";
			for ( $i = 0; $i < intval( $_POST['iSortingCols'] ); $i ++ ) {
				if ( $_POST[ 'bSortable_' . intval( $_POST[ 'iSortCol_' . $i ] ) ] == "true" ) {
					$sOrder .= $_POST[ 'mDataProp_' . intval( $_POST[ 'iSortCol_' . $i ] ) ]  . "
				 	" . ( $_POST[ 'sSortDir_' . $i ] ) . ", ";
				}
			}

			$sOrder = substr_replace( $sOrder, "", - 2 );
			if ( $sOrder == "ORDER BY" ) {
				$sOrder = "";
			}
		}


		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
//		$sWhere = "";
//		if ( $_POST['sSearch'] != "" ) {
//			$sWhere = "WHERE (";
//			for ( $i = 0; $i < count( $aColumns ); $i ++ ) {
//				$sWhere .= $aColumns[ $i ] . " LIKE '%" . ( $_POST['sSearch'] ) . "%' OR ";
//			}
//			$sWhere = substr_replace( $sWhere, "", - 3 );
//			$sWhere .= ')';
//		}
//
//		/* Individual column filtering */
//		for ( $i = 0; $i < count( $aColumns ); $i ++ ) {
//			if ( $_POST[ 'bSearchable_' . $i ] == "true" && $_POST[ 'sSearch_' . $i ] != '' ) {
//				if ( $sWhere == "" ) {
//					$sWhere = "WHERE ";
//				} else {
//					$sWhere .= " AND ";
//				}
//				$sWhere .= $aColumns[ $i ] . " LIKE '%" . ( $_POST[ 'sSearch_' . $i ] ) . "%' ";
//			}
//		}

/*		$customFilters = array(
			'Title' => $_POST['Title'],
			'Description' => $_POST['Description'],
			'Commission' => $_POST['Commission'],
			'Gravity' => $_POST['Gravity'],
			'PercentPerSale' => $_POST['PercentPerSale'],
			'PercentPerRebill' => $_POST['PercentPerRebill'],
			'AverageEarningsPerSale' => $_POST['AverageEarningsPerSale'],
			'InitialEarningsPerSale' => $_POST['InitialEarningsPerSale'],
			'TotalRebillAmt' => $_POST['TotalRebillAmt'],
			'Referred' => $_POST['Referred']
		);

		$customWhere = "";

		foreach($customFilters as $key=>$column){

			if($column != ''){
				if($customWhere == ""){
					$customWhere = "WHERE ";
				}else{
					$customWhere .= " AND ";
				}

				if($key == 'Title'){
					$customWhere .= $key . " LIKE '%" . ($column) . "%' ";
				}elseif($key == 'Description'){
					$customWhere .= $key . " LIKE '%" . ($column) . "%' ";
				}else{
					$customWhere .= $key . " >= " . ($column) . " ";
				}
			}
		}*/

		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery  = "
		SELECT SQL_CALC_FOUND_ROWS " . str_replace( " , ", " ", implode( ", ", $aColumns ) ) . "
		FROM   $sTable
		$sOrder
		$sLimit
	";
//        echo $sQuery;
		$rResult = $wpdb->get_results( $sQuery, ARRAY_A );

		/* Data set length after filtering */
		$sQuery             = "
		SELECT FOUND_ROWS()
	";
		$aResultFilterTotal = $wpdb->get_results( $sQuery, ARRAY_A );
		$iFilteredTotal     = $aResultFilterTotal[0]['FOUND_ROWS()'];

		/* Total data set length */
		$sQuery       = "
		SELECT COUNT(" . $sIndexColumn . ")
		FROM   $sTable
	";
		$aResultTotal = $wpdb->get_results( $sQuery, ARRAY_A );
		$iTotal       = $aResultTotal[0]['COUNT(article)'];


		/*
		 * Output
		 */
		$output = array(
			"sEcho"                => intval( $_POST['sEcho'] ),
			"iTotalRecords"        => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData"               => $rResult
		);

		echo json_encode( $output );

	}

	public static function check_articles() {
		global $wpdb;
		$query = 'select * from ' . self::TABLE_NAME . ' where status = "0" or status = "1.5"';
		$results = $wpdb->get_results($query, ARRAY_A);

		if(is_array($results)) {
			foreach ($results as $result) {

				$iNeedArticles_URL = 'http://ineedarticles.com/api.php';
				$iNeedArticles_API = get_option('iNeedArticles_Key');
				if (!$iNeedArticles_API) {
					wp_send_json(array("status"=>"ERROR" , "message"=>"API key is not currently set for iNeedArticles!"));
				}

				// get the post var
				$p = $_POST;
				$p['action'] = "batchArticles";

				// batch id
				$p['batch'] = $result["batch"];


				// set username and api
				$p['apikey'] = $iNeedArticles_API;


				// make query
				$URL =  $iNeedArticles_URL;

				//print data
				$json = GKTY_Page_Project::makeRequest($URL, array('proxy'=>FALSE, 'method'=>'POST', 'data'=>http_build_query($p)));
				$json = json_decode($json, true);

				if (!$json) {
					continue;
				} else {
					if (($json['success'] == 1)) {

                        foreach($json["output"] as $output) {
                            if ($output["status"] != 0) {

                                if ($output["status"] == "1.5") {
                                    $query = 'UPDATE ' . self::TABLE_NAME;
                                    $query .= 'SET `status` = 1.5';
                                    $query .= 'WHERE `article` =' . $output["article"];

                                    $wpdb->query( $query );
                                }

                                if ($output["status"] == "2") {

                                    $r = $_POST;
                                    $r['action'] = "batchArticle";

                                    // batch id
                                    $r['article'] = $output["article"];

                                    // set username and api
                                    $r['apikey'] = $iNeedArticles_API;

                                    $json_new = GKTY_Page_Project::makeRequest($URL, array('proxy'=>FALSE, 'method'=>'POST', 'data'=>http_build_query($r)));
                                    $json_new = json_decode($json_new, true);

                                    if (!$json_new) {
                                        continue;
                                    } else {
                                        if ($json_new['success'] == 1) {

                                            $what = array(
                                                'status' => 2,
                                                'title' => $json_new["output"]["title"],
                                                'body' => $json_new["output"]["body"],
                                                'spun' => $json_new["output"]["spun"]
                                            );

                                            $where = array(
                                                'article' => $json_new["output"]["article"]
                                            );

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

                                            $wpdb->query( $query );

                                        }else{
                                            continue;
                                        }
                                    }
                                }
                            }
                         }
					}
				}
			}
			wp_send_json(array("status"=>"OK" , "message"=>"All done!"));
		}else{
			wp_send_json(array("status"=>"OK" , "message"=>"No articles to recheck!"));
		}

	}

	public static function remove_article_db() {
		global $wpdb;

		$data = $_POST;
		$article   = $data['article'];

		$wpdb->query( "DELETE FROM " . self::TABLE_NAME . " WHERE article = '$article'" );

		wp_send_json(array("status"=>"OK" , "message"=>"Successfully removed!"));
	}

	public static function load_article_db() {
		global $wpdb;

		$data = $_POST;
		$article   = $data['article'];

		$query = 'select * from ' . self::TABLE_NAME . ' where article = "' . $article . '";';
		$results = $wpdb->get_row($query, ARRAY_A);

		wp_send_json(array("status"=>"OK", "data"=>$results));
	}

	public static function create_page_post () {
		$data = $_POST;
		global $user_ID;

		$post_type = $data['post_type'];
		$post_content = $data['post_content'];
		$group_h1 = $data['post_title'];

		$page['post_type']    = $post_type;
		$page['post_content'] = $post_content;
		$page['post_parent']  = 0;
		$page['post_author']  = $user_ID;
		$page['post_status']  = 'publish';
		$page['post_title']   = $group_h1;
		$pageid = wp_insert_post ($page);

		$result = array(
			'result' => 'success',
			'url' => "/wp-admin/post.php?post={$pageid}&action=edit"
		);
		wp_send_json($result);
	}

	public static function check_single_article() {
		global $wpdb;

		$iNeedArticles_URL = 'http://ineedarticles.com/api.php';
		$iNeedArticles_API = get_option('iNeedArticles_Key');
		if (!$iNeedArticles_API) {
			wp_send_json(array("status"=>"ERROR" , "message"=>"API key is not currently set for iNeedArticles!"));
		}

		$post = $_POST;

		// get the post var
		$p = $_POST;
		$p['action'] = "batchArticle";

		// batch id
		$p['article'] = $post["article"];

		// set username and api
		$p['apikey'] = $iNeedArticles_API;

		// make query
		$URL =  $iNeedArticles_URL;

		//print data
		$json = GKTY_Page_Project::makeRequest($URL, array('proxy'=>FALSE, 'method'=>'POST', 'data'=>http_build_query($p)));
		$json = json_decode($json, true);

		if (!$json) {
			wp_send_json(array("status"=>"ERROR" , "message"=>"There was an error on iNeedArticles side, please try again later!"));
		}else {
            if ($json['output']["status"] != 0) {

                if ($json["status"] == "1.5") {
                    $query = 'UPDATE ' . self::TABLE_NAME;
                    $query .= 'SET `status` = 1.5';
                    $query .= 'WHERE `article` =' . $json['output']["article"];

                    $wpdb->query($query);
                }

                if ($json['output']["status"] == "2") {

                    $what = array(
                        'status' => 2,
                        'title' => $json["output"]["title"],
                        'body' => $json["output"]["body"],
                        'spun' => $json["output"]["spun"]
                    );

                    $where = array(
                        'article' => $json["output"]["article"]
                    );

                    $query = "UPDATE " . self::TABLE_NAME . " SET ";
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

                    $wpdb->query($query);

                    wp_send_json(array("status" => "OK", "message" => "All done!", 'debug' => $json));
                }

			}
		}

	}

	public static function append_post_page() {
		$data = $_POST;

		if ($data['append'] == "true") {
			$my_id = $data['post_id'];
			$post_id_data = get_post($my_id);
			$content_data = $post_id_data->post_content;

			$post_content = $content_data . '<br>' . $data['post_content'];
		}else{
			$post_content = $data['post_content'];
		}

		$my_post = array(
			'ID'           => $data['post_id'],
			'post_content' => $post_content
		);

		// Update the post into the database
		wp_update_post( $my_post );

		$result = array(
			'result' => 'success',
			'url' => "/wp-admin/post.php?post={$data['post_id']}&action=edit"
		);
		wp_send_json($result);
	}
}
