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
	$table_hasil	= "rekap_suara_dpr";
	$table_caleg	= "caleg_dpr";
	$qwhere			= "";

	if (! empty ($dapil_id)) {
		$qwhere .=" and dapil_id = ". $dapil_id;
	}
	if (! empty ($kecamatan_id)) {
		$qwhere .=" and kecamatan_id = ". $kecamatan_id;
	}
	if (! empty ($kelurahan_id)) {
		$qwhere .=" and kelurahan_id = ". $kelurahan_id;
	}
	if (! empty ($tps_id)) {
		$qwhere .=" and tps_id = ". $tps_id;
	}
	if (! empty ($kode_saksi)) {
		$qwhere .=" and kode_saksi = '". $kode_saksi ."' ";
	}

	// Get data
	$q	="
select	ifnull(sum(A.jumlah),0)		as jumlah
,		ifnull(sum(A.rusak),0)		as rusak
,		ifnull(sum(A.sisa),0)		as sisa
,		ifnull(sum(A.sah),0)		as sah
,		ifnull(sum(A.tidak_sah),0)	as tidak_sah
from	". $table_hasil ."			as A
where	1 = 1 ". $qwhere;

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
