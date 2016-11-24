<?php
/**
 * Model: GKTY_Model_Clickbank
 * @package GKTY_Model
 */

/**
 * Model class <i>GKTY_Model_Clickbank</i> represents group
 * @package GKTY_Model
 */

class GKTY_Model_Clickbank {
	/**
	 * Tab table name.
	 */
	const TABLE_NAME = 'gkty_clickbank';

	public static function datatableClickbank() {
		global $wpdb;

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * Easy set variables
		 */

		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */
		$aColumns = array(
			'id',
			'Category',
			'Tag',
			'PopularityRank',
			'Title',
			'Description',
			'HasRecurringProducts',
			'Gravity',
			'PercentPerSale',
			'PercentPerRebill',
			'AverageEarningsPerSale',
			'InitialEarningsPerSale',
			'TotalRebillAmt',
			'Referred',
			'Commission',
			'ActivateDate',
			'ActivateDate'
		);

		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id";

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

        $customFilters = array(
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
        }

		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery  = "
		SELECT SQL_CALC_FOUND_ROWS " . str_replace( " , ", " ", implode( ", ", $aColumns ) ) . "
		FROM   $sTable
		$customWhere
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
		$iTotal       = $aResultTotal[0]['COUNT(id)'];


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

	public static function downloadClickbank() {
		$db = $_SERVER["DOCUMENT_ROOT"] . "/wp-content/uploads/db.zip";
		$fb = $_SERVER["DOCUMENT_ROOT"] . "/wp-content/uploads/";
		$fn = 'marketplace_feed_v2.xml';
		$fd = 'marketplace_feed_v2.dtd';
		@unlink( $db );
		@unlink( $fb . $fn );
		@unlink( $fb . $fd );
		file_put_contents( $db, file_get_contents( "http://accounts.clickbank.com/feeds/marketplace_feed_v2.xml.zip" ) );

		if ( class_exists( 'ZipArchive' ) ) {
			$zip = new ZipArchive;
			$res = $zip->open( $db );
			if ( $res === true ) {
				$zip->extractTo( $fb );
				$zip->close();
			}
		} else {
			echo 'Did not unzip correctly!';
			return;
		}

		// check if unzipped
		if (!file_exists($fb . $fn )) {
			echo 'Did not unzip correctly!';
			return;
		}

		// Unzipped, now push to DB
		$xml = file_get_contents( $fb . $fn );
		$xml = simplexml_load_string( $xml );
		if ( $xml === false ) {
			echo "Failed loading XML: ";
			foreach ( libxml_get_errors() as $error ) {
				echo "<br>", $error->message;
			}
		} else {

			// Add records to db
			global $wpdb;
			$wpdb->query( 'TRUNCATE TABLE ' . self::TABLE_NAME );

			foreach ( $xml->Category as $c ) {
				$cat = $c->Name;
				foreach ( $c->Site as $s ) {
					self::addRecord( array(
						'Category'               => $cat,
						'Tag'                    => $s->Id,
						'PopularityRank'         => $s->PopularityRank,
						'Title'                  => $s->Title,
						'Description'            => $s->Description,
						'HasRecurringProducts'   => $s->HasRecurringProducts,
						'Gravity'                => $s->Gravity,
						'PercentPerSale'         => $s->PercentPerSale,
						'PercentPerRebill'       => $s->PercentPerRebill,
						'AverageEarningsPerSale' => $s->AverageEarningsPerSale,
						'InitialEarningsPerSale' => $s->InitialEarningsPerSale,
						'TotalRebillAmt'         => $s->TotalRebillAmt,
						'Referred'               => $s->Referred,
						'Commission'             => $s->Commission,
						'ActivateDate'           => $s->ActivateDate
					) );
				}
			}

			echo 'Success!';
		}

	}

	/**
	 * Create table.
	 */
	public static function createTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$creation_query =
			'CREATE TABLE ' . self::TABLE_NAME . ' (
			`id` int(20) NOT NULL AUTO_INCREMENT,
			`Category` varchar(255),
			`Tag` varchar(255),
			`PopularityRank` int(5),
			`Title` longtext,
			`Description` longtext,
			`HasRecurringProducts` varchar(255),
			`Gravity` float(11,5),
			`PercentPerSale` float(11,5),
			`PercentPerRebill` float(11,5),
			`AverageEarningsPerSale` float(11,5),
			`InitialEarningsPerSale` float(11,5),
			`TotalRebillAmt` float(11,5),
			`Referred` float(11,5),
			`Commission` int(5),
			`ActivateDate` date,
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
	 * Add Product.
	 */
	public static function addRecord( $data ) {
		global $wpdb;

		$query  = "INSERT INTO " . self::TABLE_NAME;
		$names  = '(';
		$values = '(';
		foreach ( $data as $k => $v ) {
			$v = str_replace( '\'', '\\\'', $v );
			$v = str_replace( '"', '\\"', $v );
			$names .= $k . ',';
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

		return $index;
	}

}
