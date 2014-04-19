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
	$rs = inputdata_rekap_read ($table_hasil, $table_caleg);

	$r = array (
		'success'	=> true
	,	'data'		=> $rs
	,	'total'		=> 0
	);
} catch (Exception $e) {
	$r['data']	= $e->getMessage ();
}

require_once "../../json_end.php";
