<?php
/**
 * Model: GKTY_Model_Pixabay
 * @package GKTY_Model
 */

/**
 * Model class <i>GKTY_Model_Pixabay</i> represents group
 * @package GKTY_Model
 */

use ApaiIO\ApaiIO;
use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;

class GKTY_Model_Pixabay {

	public static function searchImages() {
		$PixabayURL = 'https://pixabay.com/api/';
		$PixabayAPI = get_option('PixabayAPI');
		$PixabayUsername = get_option('PixabayUsername');
		if (!$PixabayAPI || !$PixabayUsername) {
			echo "ERROR: API key is not currently set for Pixabay!";
			return false;
		}

		// get the post var
		$p = $_POST;
		unset($p['action']);

		// defaults
		if (!isset($p['per_page'])) $p['per_page'] = 20;
		if (!isset($p['page'])) $p['page'] = 1;
		if (!isset($p['order'])) $p['order'] = 'popular';

		// set username and api
		//$p['username'] = $PixabayUsername;
		$p['key'] = $PixabayAPI;

		// make query
		$URL =  $PixabayURL.'?'.http_build_query($p);

		//print data
		header('Content-Type: application/json');
		$content = GKTY_Page_Project::makeRequest($URL, array('proxy'=>FALSE));
		$json = json_decode($content, true);
		if (!$json) {
			echo "ERROR: Failed to parse Pixabay JSON response! --- $content";
			return false;
		} else {
			$json['current_page'] = $p['page'];
			$json['total_pages'] = (int)($json['totalHits'] / $p['per_page']) + 1;
			wp_send_json($json);
		}
		return null;

	}

	public static function getImages() {
		$p = $_POST;
		$uploads = wp_upload_dir();
		$image_url = $p['image_url'];
		$new_name = $p['new_name'];

		if (basename($image_url) != $new_name) {
			$new_file_name = $new_name;
		}else{
			$new_file_name = basename($image_url);
		}

		$filename = wp_unique_filename( $uploads['path'], $new_file_name, $unique_filename_callback = null );
		$wp_file_type = wp_check_filetype($filename, null );
		$full_path_file_name = $uploads['path'] . "/" . $filename;

		$image_string = self::fetch_image($image_url);

		$fileSaved = file_put_contents($uploads['path'] . "/" . $filename, $image_string);
		if ( !$fileSaved ) {
			wp_send_json(array("status"=>"ERROR", "message"=>"The file cannot be saved."));
		}

		$attachment = array(
			'post_mime_type' => $wp_file_type['type'],
			'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
			'post_content' => '',
			'post_status' => 'inherit',
			'guid' => $uploads['url'] . "/" . $filename
		);
		$attach_id = wp_insert_attachment( $attachment, $full_path_file_name, 0 );
		if ( !$attach_id ) {
			wp_send_json(array("status"=>"ERROR", "message"=>"Failed to save record into database."));
		}
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		$attach_data = wp_generate_attachment_metadata( $attach_id, $full_path_file_name );
		wp_update_attachment_metadata( $attach_id,  $attach_data );

		wp_send_json(array("status"=>"OK", "attach_id"=>$attach_id, "attach_data"=>$attach_data));
	}

	/**
	Function for downloading pixabay images
	 */
	function fetch_image($url) {
		if ( function_exists("curl_init") ) {
			return self::curl_fetch_image($url);
		} elseif ( ini_get("allow_url_fopen") ) {
			return self::fopen_fetch_image($url);
		}
	}
	function curl_fetch_image($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$image = curl_exec($ch);
		curl_close($ch);
		return $image;
	}
	function fopen_fetch_image($url) {
		$image = file_get_contents($url, false, $context);
		return $image;
	}
}
