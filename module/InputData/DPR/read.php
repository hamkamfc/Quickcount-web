<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../json_begin.php";

try {
	$dapil_id		= $_GET["dapil_id"];
	$kecamatan_id	= $_GET["kecamatan_id"];
	$kelurahan_id	= $_GET["kelurahan_id"];
	$tps_id			= $_GET["tps_id"];
	$kode_saksi		= $_GET["kode_saksi"];
	$table_hasil	= "hasil_dpr";
	$table_caleg	= "caleg_dpr";
	$qwhere			= "";

	if (! empty ($dapil_id)) {
		$qwhere .= " and dapil_id = ". $dapil_id;
	}
	if (! empty ($kecamatan_id)) {
		$qwhere .= " and kecamatan_id = ". $kecamatan_id;
	}
	if (! empty ($kelurahan_id)) {
		$qwhere .= " and kelurahan_id = ". $kelurahan_id;
	}
	if (! empty ($tps_id)) {
		$qwhere .= " and tps_id = ". $tps_id;
	}
	if (! empty ($kode_saksi)) {
		$qwhere .= " and kode_saksi = '". $kode_saksi ."'";
	}

	$q=
"
select	P.id	as partai_id
,		P.nama	as partai_nama
,		C.id	as caleg_id
,		C.nama	as caleg_nama
,		(
			select	ifnull(sum(hasil),0) as v
			from	". $table_hasil ."
			where	caleg_id	= C.id
			and		partai_id	= P.id
			". $qwhere ."
		)		as hasil
from	". $table_caleg ."	TC
,		partai				P
,		caleg				C
where	TC.partai_id		= P.id
and		TC.caleg_id			= C.id
and		TC.dapil_id			= ". $dapil_id ."
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
