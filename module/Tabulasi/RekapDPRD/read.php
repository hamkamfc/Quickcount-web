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
	$qwhere			= "";
	$qgroup			= "";
	$table_hasil	= "rekap_suara_dprd";

	if (! empty ($dapil_id)) {
		$qwhere .=" and dapil_id = ". $dapil_id;
		$qgroup .=" dapil_id ";
	}
	if (! empty ($kecamatan_id)) {
		$qwhere .=" and kecamatan_id = ". $kecamatan_id;
		$qgroup .=" , kecamatan_id ";
	}
	if (! empty ($kelurahan_id)) {
		$qwhere .=" and kelurahan_id = ". $kelurahan_id;
		$qgroup .=" , kelurahan_id ";
	}
	if (! empty ($tps_id)) {
		$qwhere .=" and tps_id = ". $tps_id;
		$qgroup .=" , tps_id ";
	}

	// Get data
	$q	="
			select	ifnull(sum(jumlah),0)		as jumlah
			,		ifnull(sum(rusak),0)		as rusak
			,		ifnull(sum(sisa),0)			as sisa
			,		ifnull(sum(sah),0)			as sah
			,		ifnull(sum(tidak_sah),0)	as tidak_sah
			,		(
						select	count(UTPS.tps_id)		as jumlah_tps
						from	(
							select	distinct tps_id
							from	". $table_hasil ."
							where	status = 1
							". $qwhere ."
						) UTPS
					)							as jumlah_tps
			from	". $table_hasil ."
			where	status = 1
		". $qwhere;

	if (! empty ($qgroup)) {
		$q .= " group by ". $qgroup;
	}

	$ps = Jaring::$_db->prepare ($q);
	$i = 1;
	$ps->execute ();
	$rs = $ps->fetchAll (PDO::FETCH_ASSOC);
	$ps->closeCursor ();

	$r = array (
		'success'	=> true
	,	'data'		=> $rs
	,	'total'		=> $t
	,	'q'			=> $q
	);
} catch (Exception $e) {
	$r['data']	= $e->getMessage ();
}

require_once "../../json_end.php";