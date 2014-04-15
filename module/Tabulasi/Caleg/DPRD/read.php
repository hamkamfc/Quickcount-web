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
	$tps_id			= $_GET["tps_id"];
	$table_hasil	= "hasil_dprd";
	$table_caleg	= "caleg_dprd";
	$qwhere			= "";

	if (! empty ($dapil_id)) {
		$qwhere .= " and HD.dapil_id = ". $dapil_id;
	}
	if (! empty ($kecamatan_id)) {
		$qwhere .= " and HD.kecamatan_id = ". $kecamatan_id;
	}
	if (! empty ($kelurahan_id)) {
		$qwhere .= " and HD.kelurahan_id = ". $kelurahan_id;
	}
	if (! empty ($tps_id)) {
		$qwhere .= " and HD.tps_id = ". $tps_id;
	}

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
where	HD.status		= 1
and		HD.caleg_id		= CD.id
and		CD.caleg_id		= C.id
and		HD.partai_id	= P.id
". $qwhere ."
group by HD.partai_id, CD.caleg_id
order by HD.partai_id, CD.caleg_id
) X
, (
	select	ifnull(sum(HD.hasil),0)	as v
	from	". $table_hasil ."			HD
	where	HD.status				= 1
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

require_once "../../../json_end.php";