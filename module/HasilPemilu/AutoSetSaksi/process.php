<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../json_begin.php";

function updateStatusHasil ($table_hasil)
{
	$q=
"
	update	". $table_hasil ." H1
	,		(
		select	distinct
				tps_id
		from	". $table_hasil ."
		where	status = 0
	) H2
	set		H1.status = 0
	where	H1.status = 1
	and		H1.tps_id = H2.tps_id
";

	Jaring::$_db->beginTransaction ();

	$ps = Jaring::$_db->prepare ($q);
	$ps->execute ();
	$ps->closeCursor ();

	Jaring::$_db->commit ();

	$q=
"
update ". $table_hasil ." HD
, (
	select A.*
	from (
		select distinct
		  S.dapil_id
		, S.kecamatan_id
		, S.kelurahan_id
		, S.tps_id
		, (
			select	kode_saksi
			from	". $table_hasil ."
			where	tps_id = S.tps_id
			and		status = 0
			limit	0,1
		) as kode_saksi
		, S.status
		from	". $table_hasil ." S
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
				from	". $table_hasil ."
				where	tps_id = S.tps_id
				and		status = 1
				limit	0,1
			) as kode_saksi
			from	". $table_hasil ." S
			where	status	= 1
		) B
	)
) X
set		HD.status = 1
where	HD.dapil_id		= X.dapil_id
and		HD.kecamatan_id	= X.kecamatan_id
and		HD.kelurahan_id	= X.kelurahan_id
and		HD.tps_id		= X.tps_id
and		HD.kode_saksi	= X.kode_saksi;
";

	Jaring::$_db->beginTransaction ();

	$ps = Jaring::$_db->prepare ($q);
	$ps->execute ();
	$ps->closeCursor ();

	Jaring::$_db->commit ();
}

$rs = [];
$t	= 0;

try {

	updateStatusHasil ("hasil_dpr");
	updateStatusHasil ("hasil_dprd");
	updateStatusHasil ("rekap_suara_dpr");
	updateStatusHasil ("rekap_suara_dprd");

	$r = array (
		'success'	=> true
	,	'data'		=> $rs
	,	'total'		=> $t
	);
} catch (Exception $e) {
	$r['data'] = $e->getMessage ();

	echo json_encode ($r);

	die ();
}

require_once "../../json_end.php";
