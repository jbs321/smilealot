<?php
/**
 * Model: GKTY_Model_Exif
 * @package GKTY_Model
 */

/**
 * Model class <i>GKTY_Model_Exif</i> represents group
 * @package GKTY_Model
 */

class GKTY_Model_Exif {

	/**
	 * @param $filename - Location of the file to read.
	 *
	 * @return array
	 * @throws PelInvalidDataException
	 */
	public static function read_image_info($filename) {
        if (!file_exists($filename)) {
            return array('lat'=>'', 'lon'=>'', 'desc'=>'');
		}
		$jpeg = new PelJpeg($filename);
		if (!is_object($jpeg)) return array('lat'=>'', 'lon'=>'', 'desc'=>'');
        $exif = $jpeg->getExif();
        if (!is_object($exif)) return array('lat'=>'', 'lon'=>'', 'desc'=>'');
		$tiff = $exif->getTiff();
        if (!is_object($tiff)) return array('lat'=>'', 'lon'=>'', 'desc'=>'');
		try {
            $ifd0 = $tiff->getIfd();
            return self::GPS_Get($ifd0);
        } catch (Exception $error) {
            return array('lat'=>'', 'lon'=>'', 'desc'=>'');
        }
	}

	/**
	 * @param $filename - Location of the file to write.
	 * @param $lat - Latitude.
	 * @param $lon - Longitude.
	 * @param $desc - Image Description.
	 *
	 * @return bool
	 * @throws PelInvalidDataException
	 */
	public static function write_image_info($filename, $lat, $lon, $desc) {


		if (!file_exists($filename)) {
			return true;
		}

		$pelJpeg = new PelJpeg($filename);

		$pelExif = $pelJpeg->getExif();
		if ($pelExif == null) {
			$pelExif = new PelExif();
			$pelJpeg->setExif($pelExif);
		}

		$pelTiff = $pelExif->getTiff();
		if ($pelTiff == null) {
			$pelTiff = new PelTiff();
			$pelExif->setTiff($pelTiff);
		}

		$pelIfd0 = $pelTiff->getIfd();
		if ($pelIfd0 == null) {
			$pelIfd0 = new PelIfd(PelIfd::IFD0);
			$pelTiff->setIfd($pelIfd0);
		}

		$pelIfd0->addEntry(new PelEntryAscii(
			PelTag::IMAGE_DESCRIPTION, $desc));

		$pelSubIfdGps = new PelIfd(PelIfd::GPS);
		$pelIfd0->addSubIfd($pelSubIfdGps);

		self::GPS_Set($pelSubIfdGps, $lat, $lon);

		$pelJpeg->saveFile($filename);

		return true;
	}

	private static function GPS_Set( $pelSubIfdGps, $latitudeDegreeDecimal, $longitudeDegreeDecimal) {
		$latitudeDegreeMinuteSecond = self::degreeDecimalToDegreeMinuteSecond(abs($latitudeDegreeDecimal));
		$longitudeDegreeMinuteSecond = self::degreeDecimalToDegreeMinuteSecond(abs($longitudeDegreeDecimal));

		$longitudeRef= ($longitudeDegreeDecimal >= 0) ? 'E' : 'W';
		$latitudeRef = ($latitudeDegreeDecimal >= 0) ? 'N' : 'S';

		$pelSubIfdGps->addEntry(new PelEntryAscii(
			PelTag::GPS_LATITUDE_REF, $latitudeRef));
		$pelSubIfdGps->addEntry(new PelEntryRational(
			PelTag::GPS_LATITUDE,
			array($latitudeDegreeMinuteSecond['degree'], 1),
			array($latitudeDegreeMinuteSecond['minute'], 1),
			array(round($latitudeDegreeMinuteSecond['second'] * 1000), 1000)));
		$pelSubIfdGps->addEntry(new PelEntryAscii(
			PelTag::GPS_LONGITUDE_REF, $longitudeRef));
		$pelSubIfdGps->addEntry(new PelEntryRational(
			PelTag::GPS_LONGITUDE,
			array($longitudeDegreeMinuteSecond['degree'], 1),
			array($longitudeDegreeMinuteSecond['minute'], 1),
			array(round($longitudeDegreeMinuteSecond['second'] * 1000), 1000)));
	}

	private static function GPS_Get($ifd0) {
		$desc = $ifd0->getEntry(PelTag::IMAGE_DESCRIPTION);
		$gps = $ifd0->getSubIfd(PelIfd::GPS);
		if ( !$gps )
		{
            $ar = array(
                'lat'=>'',
                'lon'=>''
            );

            if ( !$desc ) {
                $ar['desc'] = '';
            } else {
                $ar['desc'] = $desc->getValue();
            }

            return $ar;

		} else {
			$lat = $gps->getEntry(PelTag::GPS_LATITUDE);
			$lon = $gps->getEntry(PelTag::GPS_LONGITUDE);

            $lat_ref = $gps->getEntry(PelTag::GPS_LATITUDE_REF);
            $lon_ref = $gps->getEntry(PelTag::GPS_LONGITUDE_REF);

            $lat_ref = $lat_ref->getValue();
            $lon_ref = $lon_ref->getValue();

            $lat = $lat->getValue();
			$lon = $lon->getValue();
			$lat = self::DMStoDEC($lat[0][0], $lat[1][0], ($lat[2][0] / 1000));
			$lon = self::DMStoDEC($lon[0][0], $lon[1][0], ($lon[2][0] / 1000));


            if ($lon_ref == 'W') {
                $lon = -$lon;
            }
            if ($lat_ref == 'S') {
                $lat = -$lat;
            }

            if ( !$desc ) {
                $desc = '';
            } else {
                $desc = $desc->getValue();
            }

			return array('lat'=>$lat, 'lon'=>$lon, 'desc'=>$desc);
		}
	}

	private static function degreeDecimalToDegreeMinuteSecond($degreeDecimal) {
		$degree = floor($degreeDecimal);
		$remainder = $degreeDecimal - $degree;
		$minute = floor($remainder * 60);
		$remainder = ($remainder * 60) - $minute;
		$second = $remainder * 60;
		return array('degree' => $degree, 'minute' => $minute, 'second' => $second);
	}

	private static function DMStoDEC($deg,$min,$sec)
	{
		return $deg + $min / 60 + $sec / 3600;
	}
}
