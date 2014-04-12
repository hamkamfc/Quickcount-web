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
	$table_hasil	= "hasil_dprd";
	$table_caleg	= "caleg_dprd";
	$qwhere			= "";

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

	if ($dapil_id !== 0 && $dapil_id !== null) {
		$qwhere .=" and HD.dapil_id = ". $dapil_id;
	}
	if ($kecamatan_id !== 0 && $kecamatan_id !== null) {
		$qwhere .=" and HD.kecamatan_id = ". $kecamatan_id;
	}
	if ($kelurahan_id !== 0 && $kelurahan_id !== null) {
		$qwhere .=" and HD.kelurahan_id = ". $kelurahan_id;
	}
	if ($tps_id !== 0 && $tps_id !== null) {
		$qwhere .=" and HD.tps_id = ". $tps_id;
	}
	if ($kode_saksi !== '' && $kode_saksi !== null) {
		$qwhere .=" and HD.kode_saksi = '". $kode_saksi ."' ";
	}

	// Get data
	$q=
"
select	X.partai_nama
,		X.caleg_nama
,		X.hasil
,		round(((X.hasil / Y.v) * 100), 2) as persentase
from (
select	HD.partai_id
,		CD.caleg_id
,		P.nama					as partai_nama
,		C.nama					as caleg_nama
,		ifnull(sum(hasil),0)	as hasil
from	". $table_hasil ."	HD
,		". $table_caleg ."	CD
,		caleg		C
,		partai		P
where	1				= 1
and		HD.caleg_id		= CD.id
and		CD.caleg_id		= C.id
and		HD.partai_id	= P.id
". $qwhere ."
group by HD.partai_id, CD.caleg_id
order by HD.partai_id, CD.caleg_id
) X
, (
	select	ifnull(sum(HD.hasil),0)	as v
	from	". $table_hasil ."		HD
	where	1						= 1
	". $qwhere ."
) Y
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