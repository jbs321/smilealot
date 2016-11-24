<?php
/**
 * Model: GKTY_Model_JSON_LD
 * @package GKTY_Model
 */

/**
 * Model class <i>GKTY_Model_JSON_LD</i> represents group
 * @package GKTY_Model
 */

class GKTY_Model_JSON_LD {

    /*
     * Save options for JSON LD
     */
	public static function ajaxSaveJSONLD() {

        $p = $_POST;

        unset($p['action']);
        // loop through all variables and write them in db
        foreach($p as $k=>$v) {
            if( !get_option( $k ) ) {
                add_option($k, $v);
            } else {
                update_option($k, $v);
            }
        }
        echo 'Success!';
	}

    /*
     * Generate JSON LD from saved options
     */

    public static function generateJSONLD(){

        $opt = get_option('jsonld');

        if($opt){
            $jsonld = array();

            $jsonld['@context'] = 'http://schema.org';
            $jsonld['@type'] = 'LocalBusiness';
            if(isset($opt['url']) || !empty($opt['url']))
                $jsonld['url'] = $opt['url'];
            if(isset($opt['facebook']) && !empty($opt['facebook']))
                $jsonld['sameAs'][] = $opt['facebook'];
            if(isset($opt['twitter']) && !empty($opt['twitter']))
                $jsonld['sameAs'][] = $opt['twitter'];
            if(isset($opt['google_plus']) && !empty($opt['google_plus']))
                $jsonld['sameAs'][] = $opt['google_plus'];
            if(isset($opt['instagram']) && !empty($opt['instagram']))
                $jsonld['sameAs'][] = $opt['instagram'];
            if(isset($opt['youtube']) && !empty($opt['youtube']))
                $jsonld['sameAs'][] = $opt['youtube'];
            if(isset($opt['linkedin']) && !empty($opt['linkedin']))
                $jsonld['sameAs'][] = $opt['linkedin'];

            if(isset($opt['name']) && !empty($opt['name']))
                $jsonld['name'] = $opt['name'];

            if(isset($opt['description']) && !empty($opt['description']))
                $jsonld['description'] = $opt['description'];

            if(isset($opt['url']) && !empty($opt['url']))
                $jsonld['url'] = $opt['url'];

            if(isset($opt['email']) && !empty($opt['email']))
                $jsonld['email'] = $opt['email'];

            if(isset($opt['email']) && !empty($opt['email']))
                $jsonld['telephone'] = $opt['telephone'];

            $jsonld['address'] = array(
                '@type' => 'PostalAddress'
            );
            if(isset($opt['addressLocality']) && !empty($opt['addressLocality']))
                $jsonld['address']['addressLocality'] = $opt['addressLocality'];

            if(isset($opt['addressRegion']) && !empty($opt['addressRegion']))
                $jsonld['address']['addressRegion'] = $opt['addressRegion'];

            if(isset($opt['postalCode']) && !empty($opt['postalCode']))
                $jsonld['address']['postalCode'] = $opt['postalCode'];

            if(isset($opt['streetAddress']) && !empty($opt['streetAddress']))
                $jsonld['address']['streetAddress'] = $opt['streetAddress'];

            if(empty($opt['addressLocality']) && empty($opt['addressRegion']) && empty($opt['postalCode']) && empty($opt['streetAddress'])){
                unset($jsonld['address']);
            }
            if(!isset($opt['addressLocality']) && !isset($opt['addressRegion']) && !isset($opt['postalCode']) && !isset($opt['streetAddress'])){
                unset($jsonld['address']);
            }

            $jsonld['geo'] = array(
                '@type' => 'GeoCoordinates'
            );

            if(isset($opt['latitude']) && !empty($opt['latitude']))
                $jsonld['geo']['latitude'] = $opt['latitude'];

            if(isset($opt['longitude']) && !empty($opt['longitude']))
                $jsonld['geo']['longitude'] = $opt['longitude'];

            if(empty($opt['latitude']) && empty($opt['latitude'])){
                unset($jsonld['geo']);
            }
            if(!isset($opt['longitude']) && !isset($opt['longitude'])){
                unset($jsonld['geo']);
            }

            $jsonld = json_encode($jsonld, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

            echo '<script type="application/ld+json">';
            echo $jsonld;
            echo '</script>';
        }

    }
}
