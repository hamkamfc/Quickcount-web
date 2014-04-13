<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../../json_begin.php";

try {
	$query			= $_GET["query"];
	$showEmptyOnly	= $_GET["showEmptyOnly"];
	$table_hasil	= "hasil_dprd";

	if ($showEmptyOnly === "true") {
		$s = 0;
	} else {
		$s = 1;
	}

	$q_0	=
"
select A.*
from (
	select distinct
	  S.dapil_id
	, S.kecamatan_id
	, S.kelurahan_id
	, S.tps_id
	, (
		select	kode_saksi
		from	". $table_hasil. "
		where	tps_id = S.tps_id
		and		status = 0
		limit	0,1
	) as kode_saksi
	, S.status
	from	". $table_hasil. " S
	where	status	= 0
) A
where A.tps_id not in (
	select B.tps_id
	from (
		select distinct
		  S.dapil_id
		, S.kecamatan_id
		, S.kelurahan_id
		, S.tps_id
		, (
			select	kode_saksi
			from	". $table_hasil. "
			where	tps_id = S.tps_id
			and		status = 1
			limit	0,1
		) as kode_saksi
		from	". $table_hasil. " S
		where	status	= 1
	) B
)
";

	$q_1	=
"
	select distinct
	  S.dapil_id
	, S.kecamatan_id
	, S.kelurahan_id
	, S.tps_id
	, (
		select	kode_saksi
		from	". $table_hasil. "
		where	tps_id = S.tps_id
		and		status = 1
		limit	0,1
	) as kode_saksi
	, S.status
	from	". $table_hasil. " S
	where	status	= 1
";

	$q_sub	=
"
select	dapil.nama		as dapil_nama
,		kecamatan.nama	as kecamatan_nama
,		kelurahan.nama	as kelurahan_nama
,		tps.no			as tps_no
,		tps.alamat		as tps_alamat
,		XX.kode_saksi
from (
";

	if ($s === 1) {
		$q_sub .= $q_1;
	} else {
		$q_sub .= $q_0;
	}

	$q_sub .=
"
) XX
, dapil
, kecamatan
, kelurahan
, tps
where	XX.dapil_id		= dapil.id
and		XX.kecamatan_id	= kecamatan.id
and		XX.kelurahan_id	= kelurahan.id
and		XX.tps_id		= tps.id
and		dapil.nama		like ?
and		kecamatan.nama	like ?
and		kelurahan.nama	like ?
and		tps.alamat		like ?
";



	if ($showEmptyOnly === "true") {
		$q =" select count(X.kode_saksi) as total from ( ". $q_sub ." ) X ";
	} else {
		$q =" select count(X.kode_saksi) as total from ( ". $q_sub ." ) X ";
	}

	$ps = Jaring::$_db->prepare ($q);
	$i	= 1;
	$ps->bindValue ($i++, "%". $query ."%", PDO::PARAM_STR);
	$ps->bindValue ($i++, "%". $query ."%", PDO::PARAM_STR);
	$ps->bindValue ($i++, "%". $query ."%", PDO::PARAM_STR);
	$ps->bindValue ($i++, "%". $query ."%", PDO::PARAM_STR);
	$ps->execute ();
	$rs = $ps->fetchAll (PDO::FETCH_ASSOC);
	$ps->closeCursor ();

	if (count ($rs) > 0) {
		$t = (int) $rs[0]["total"];
	}

	// Get data
	$q = $q_sub ." limit	". (int) $_GET["start"] .",". (int) $_GET["limit"];

	$ps = Jaring::$_db->prepare ($q);
	$i	= 1;
	$ps->bindValue ($i++, "%". $query ."%", PDO::PARAM_STR);
	$ps->bindValue ($i++, "%". $query ."%", PDO::PARAM_STR);
	$ps->bindValue ($i++, "%". $query ."%", PDO::PARAM_STR);
	$ps->bindValue ($i++, "%". $query ."%", PDO::PARAM_STR);
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
	$r['q'] = $q;
}

require_once "../../../json_end.php";

