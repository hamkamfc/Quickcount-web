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

	$q_sub	="
select	A.nama		as dapil_nama
,		B.nama		as kecamatan_nama
,		C.nama		as kelurahan_nama
,		D.no		as tps_no
,		D.alamat	as tps_alamat
,		(
			select	E.kode_saksi
			from	saksi_default	E
			where	E.tps_id		= D.id
			and		E.type			= 1
		)			as kode_saksi
from	dapil		A
,		kecamatan	B
,		kelurahan	C
,		tps			D
where	A.id		= B.dapil_id
and		B.id		= C.kecamatan_id
and		C.id		= D.kelurahan_id
and		A.nama		like ?
and		B.nama		like ?
and		C.nama		like ?
and		D.alamat	like ?
order by A.id, B.id, C.id, D.id
";

	if ($showEmptyOnly === "true") {
		$q =" select count(X.kode_saksi) as total from ( ". $q_sub ." ) X where X.kode_saksi is null ";
	} else {
		$q =" select count(X.kode_saksi) as total from ( ". $q_sub ." ) X where X.kode_saksi is not null ";
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
	if ($showEmptyOnly === "true") {
		$q =" select X.* from ( ". $q_sub ." ) X where X.kode_saksi is null ";
	} else {
		$q =" select X.* from ( ". $q_sub ." ) X where X.kode_saksi is not null ";
	}

	$q .= " limit	". (int) $_GET["start"] .",". (int) $_GET["limit"];

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
}

require_once "../../../json_end.php";

