<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../json_begin.php";

try {
	$query			= $_GET["query"];
	$filter			= json_decode ($_GET["filter"]);
	$dapil_id		= 0;
	$kecamatan_id	= 0;
	$kelurahan_id	= 0;
	$tps_id			= 0;
	$kode_saksi		= '';

	if (count($filter) > 0) {
		for ($i = 0; $i < count($filter); $i++) {
			if ($filter[$i]->property == "dapil_id") {
				$dapil_id = $filter[$i]->value;
			} else if ($filter[$i]->property == "kecamatan_id") {
				$kecamatan_id = $filter[$i]->value;
			} else if ($filter[$i]->property == "kelurahan_id") {
				$kelurahan_id = $filter[$i]->value;
			} else if ($filter[$i]->property == "tps_id") {
				$tps_id = $filter[$i]->value;
			} else if ($filter[$i]->property == "kode_saksi") {
				$kode_saksi = $filter[$i]->value;
			}
		}
	}

	// Get data
	$q	="
select	A.id		as caleg_id
,		A.partai_id
,		(	select	B.nama
			from	partai	B
			where	B.id	= A.partai_id
		)			as partai_nama
,		A.dapil_id
,		A.no_urut	as caleg_no_urut
,		A.nama		as caleg_nama
,		(	select	C.hasil
			from	hasil_dpr	C
			where	C.caleg_id	= A.id
			and		C.dapil_id	= A.dapil_id
			and		C.partai_id	= A.partai_id
";

	if ($dapil_id !== 0 && $dapil_id !== null) {
		$q .=" and dapil_id = ". $dapil_id;
	}
	if ($kecamatan_id !== 0 && $kecamatan_id !== null) {
		$q .=" and kecamatan_id = ". $kecamatan_id;
	}
	if ($kelurahan_id !== 0 && $kelurahan_id !== null) {
		$q .=" and kelurahan_id = ". $kelurahan_id;
	}
	if ($tps_id !== 0 && $tps_id !== null) {
		$q .=" and tps_id = ". $tps_id;
	}
	if ($kode_saksi !== '' && $kode_saksi !== null) {
		$q .=" and kode_saksi = '". $kode_saksi ."' ";
	}

	$q	.="
		)			as hasil
from	caleg_dpr	A
where	A.dapil_id	= ". $dapil_id ."
order by A.partai_id, A.dapil_id, A.no_urut
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

require_once "../../json_end.php";

