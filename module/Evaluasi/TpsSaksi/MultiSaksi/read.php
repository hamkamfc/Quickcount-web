<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../../json_begin.php";

try {
	$qlimit = " limit ". (int) $_GET['start'] .",". (int) $_GET['limit'];

	$q=
"
select	tps.no			as tps_no
,		tps.alamat		as tps_alamat
,		kelurahan.nama	as kelurahan_nama
,		kecamatan.nama	as kecamatan_nama
,		dapil.nama		as dapil_nama
,		Y.jumlah_saksi
from (
	select	X.tps_id
	,		count(X.kode_saksi)	as jumlah_saksi
	from (
	select	distinct
			dapil_id
	,		kecamatan_id
	,		kelurahan_id
	,		tps_id
	,		kode_saksi
	from	hasil_dpr
	) X
	group by tps_id
) Y
, tps
, kelurahan
, kecamatan
, dapil
where	Y.jumlah_saksi			> 1
and		Y.tps_id				= tps.id
and		tps.kelurahan_id		= kelurahan.id
and		kelurahan.kecamatan_id	= kecamatan.id
and		kecamatan.dapil_id		= dapil.id
order by tps_id
";

	$ps = Jaring::$_db->prepare (" select count(ZZZ.tps_no) as total from (". $q. ") ZZZ ");
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

