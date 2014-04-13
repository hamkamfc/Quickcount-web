<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../../json_begin.php";

try {
	$dapil_id		= $_GET["dapil_id"];
	$kecamatan_id	= $_GET["kecamatan_id"];
	$kelurahan_id	= $_GET["kelurahan_id"];

$q =
"
select	T.no		as tps_no
,		T.alamat	as tps_alamat
,		R.jumlah
,		R.rusak
,		R.sisa
,		R.sah
,		R.tidak_sah
from	rekap_suara_dpr	R
,		tps				T
where	R.tps_id		= T.id
and		R.status		= 1
";

	if ($dapil_id !== null && $dapil_id > 0) {
		$q .= " and R.dapil_id = ". $dapil_id;
	}
	if ($kecamatan_id !== null && $kecamatan_id > 0) {
		$q .= " and R.kecamatan_id = ". $kecamatan_id;
	}
	if ($kelurahan_id !== null && $kelurahan_id > 0) {
		$q .= " and R.kelurahan_id = ". $kelurahan_id;
	}

	$ps = Jaring::$_db->prepare ($q);
	$ps->execute ();
	$rs = $ps->fetchAll (PDO::FETCH_ASSOC);
	$ps->closeCursor ();

	$r = array (
		'success'	=> true
	,	'data'		=> $rs
	,	'total'		=> $t
	);
} catch (Exception $e) {
	$r['data']	= $e->getMessage ();
}

require_once "../../../json_end.php";