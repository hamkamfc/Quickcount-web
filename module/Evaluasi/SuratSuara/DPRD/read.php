<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../../json_begin.php";

$t				= 0;
$rs				= [];
$qwhere			= "";
$table_hasil	= "rekap_suara_dprd";

try {
	$query			= $_GET["query"];
	$dapil_id		= $_GET["dapil_id"];
	$kecamatan_id	= $_GET["kecamatan_id"];
	$kelurahan_id	= $_GET["kelurahan_id"];
	$qlimit			= " limit ". (int) $_GET["start"] .",". (int) $_GET["limit"];

	if ($dapil_id !== null && $dapil_id > 0) {
		$qwhere .= " and R.dapil_id = ". $dapil_id;
	}
	if ($kecamatan_id !== null && $kecamatan_id > 0) {
		$qwhere .= " and R.kecamatan_id = ". $kecamatan_id;
	}
	if ($kelurahan_id !== null && $kelurahan_id > 0) {
		$qwhere .= " and R.kelurahan_id = ". $kelurahan_id;
	}

	$qwhere .= " and T.alamat like '%". $query ."%' ";

$q =
"
	select	T.no		as tps_no
	,		T.alamat	as tps_alamat
	,		R.jumlah
	,		R.rusak
	,		R.sisa
	,		R.sah
	,		R.tidak_sah
	from	". $table_hasil	." R
	,		tps				T
	where	R.tps_id		= T.id
	and		R.status		= 1
". $qwhere;

	$ps = Jaring::$_db->prepare (" select count(XXX.tps_no) as total from (". $q ." ) XXX ");
	$ps->execute ();
	$rs = $ps->fetchAll (PDO::FETCH_ASSOC);
	$ps->closeCursor ();

	if (count ($rs) > 0) {
		$t = (int) $rs[0]["total"];
	}

	$ps = Jaring::$_db->prepare ($q . $qlimit);
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