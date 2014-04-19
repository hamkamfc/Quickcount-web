<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../json_begin.php";
require_once "../process.php";

$table_hasil	= "rekap_suara_dpr";
$table_caleg	= "caleg_dpr";

try {
	inputdata_rekap_update ($table_hasil, $table_caleg);

	$r['success']	= true;
	$r['data']		= Jaring::$MSG_SUCCESS_UPDATE;
} catch (Exception $e) {
	$r['data']		= $e->getMessage ();
}

require_once "../../json_end.php";
