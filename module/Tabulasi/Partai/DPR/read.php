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

	$q	=
"
select	id			as partai_id
,		nama		as partai_nama
,		ifnull((
			select	sum(hasil)
			from	hasil_dpr	A
			where	1 = 1
";

	if ($dapil_id !== null && $dapil_id > 0) {
		$q .= " and A.dapil_id = ". $dapil_id;
	}
	if ($kecamatan_id !== null && $kecamatan_id > 0) {
		$q .= " and A.kecamatan_id = ". $kecamatan_id;
	}
	if ($kelurahan_id !== null && $kelurahan_id > 0) {
		$q .= " and A.kelurahan_id = ". $kelurahan_id;
	}

	$q	.=
"
	and	A.kode_saksi in (
			select	distinct
					SD.kode_saksi
			from	saksi_default	SD
			where	SD.type = 1
";

	if ($dapil_id !== null && $dapil_id > 0) {
		$q .= " and SD.dapil_id = ". $dapil_id;
	}
	if ($kecamatan_id !== null && $kecamatan_id > 0) {
		$q .= " and SD.kecamatan_id = ". $kecamatan_id;
	}
	if ($kelurahan_id !== null && $kelurahan_id > 0) {
		$q .= " and SD.kelurahan_id = ". $kelurahan_id;
	}

$q .=
"
		)
		group by partai_id
		),0) as hasil
from	partai P
";

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