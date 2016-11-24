<?php
/**
 * Model: GKTY_Model_Proxy
 * @package GKTY_Model
 */

/**
 * Model class <i>GKTY_Model_Proxy</i> represents group
 * @package GKTY_Model
 */

class GKTY_Model_Proxy {
	public static function init() {
        header('Content-type: application/json');
		$proxies = array();
		$websites = explode(",", $_POST['websites']);

		foreach($websites as $w) {
			$s = @file_get_contents($w);
            preg_match_all('/([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})[:\s]+(\d{1,5})/i', $s, $matches);
            $count = count($matches[1]);

            for ($i = 0; $i < $count; $i++)
                if (self::isValidIpOrHost($matches[1][$i])) {
                    $proxies[] = $matches[1][$i].':'.$matches[2][$i];
                }
		}

        echo json_encode($proxies);
        wp_die();

	}

	private static function isValidIpOrHost($str) {
		$parts = explode(".", $str);
		$allNumbers = true;

		for ($i = 0; $i < count($parts); $i++) {
			if ("".intVal($parts[$i]) == $parts[$i]) {
				if ($i == count($parts) -1) {
					return $allNumbers;
				}
			}
			else {
				$allNumbers = false;
			}
		}
		return true;
	}
}
