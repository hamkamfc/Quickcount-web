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

	$qwhere = "";
	$qgroup	= "";

	if ($dapil_id !== 0 && $dapil_id !== null) {
		$qwhere .=" and dapil_id = ". $dapil_id;
		$qgroup .=" dapil_id ";
	}
	if ($kecamatan_id !== 0 && $kecamatan_id !== null) {
		$qwhere .=" and kecamatan_id = ". $kecamatan_id;
		$qgroup .=" , kecamatan_id ";
	}
	if ($kelurahan_id !== 0 && $kelurahan_id !== null) {
		$qwhere .=" and kelurahan_id = ". $kelurahan_id;
		$qgroup .=" , kelurahan_id ";
	}
	if ($tps_id !== 0 && $tps_id !== null) {
		$qwhere .=" and tps_id = ". $tps_id;
		$qgroup .=" , tps_id ";
	}
	if ($kode_saksi !== '' && $kode_saksi !== null) {
		$qwhere .=" and kode_saksi = '". $kode_saksi ."' ";
		$qgroup .=" , kode_saksi ";
	}


	// Get data
	$q	="
select	ifnull(sum(A.jumlah),0)		as jumlah
,		ifnull(sum(A.rusak),0)		as rusak
,		ifnull(sum(A.sisa),0)		as sisa
,		ifnull(sum(A.sah),0)		as sah
,		ifnull(sum(A.tidak_sah),0)	as tidak_sah
from	rekap_suara_dpr as A
where	1 = 1 ". $qwhere;

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
	);
} catch (Exception $e) {
	$r['data']	= $e->getMessage ();
}

require_once "../../json_end.php";