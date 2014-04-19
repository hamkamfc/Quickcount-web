<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

$dapil_id		= 0;
$kecamatan_id	= 0;
$kelurahan_id	= 0;
$tps_id			= 0;
$kode_saksi		= "";
$qwhere			= "";

function inputdata_get_param ($type)
{
	global $dapil_id, $kecamatan_id, $kelurahan_id, $tps_id, $kode_saksi
			, $qwhere;

	switch ($type) {
	case 'GET':
		$dapil_id		= $_GET["dapil_id"];
		$kecamatan_id	= $_GET["kecamatan_id"];
		$kelurahan_id	= $_GET["kelurahan_id"];
		$tps_id			= $_GET["tps_id"];
		$kode_saksi		= $_GET["kode_saksi"];
		break;
	case 'POST':
		$dapil_id		= $_POST["dapil_id"];
		$kecamatan_id	= $_POST["kecamatan_id"];
		$kelurahan_id	= $_POST["kelurahan_id"];
		$tps_id			= $_POST["tps_id"];
		$kode_saksi		= $_POST["kode_saksi"];
		break;
	}
		
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
}

function inputdata_hasil_read ($table_hasil, $table_caleg)
{
	global $qwhere, $dapil_id;

	inputdata_get_param ("GET");

	$q	=
		"
		select	P.id	as partai_id
		,		P.nama	as partai_nama
		,		TC.id	as caleg_id
		,		C.nama	as caleg_nama
		,		(
					select	ifnull(sum(hasil),0) as v
					from	". $table_hasil ."
					where	caleg_id	= TC.id
					and		partai_id	= TC.partai_id
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

	return $rs;
}

function inputdata_hasil_update ($table_hasil, $table_caleg)
{
	global $dapil_id, $kecamatan_id, $kelurahan_id, $tps_id, $kode_saksi;

	inputdata_get_param ("GET");

	$data = json_decode (file_get_contents('php://input'), true);

	$q	="
		insert	into ". $table_hasil ." (
		  dapil_id
		, kecamatan_id
		, kelurahan_id
		, tps_id
		, kode_saksi
		, partai_id
		, caleg_id
		, hasil
		) values (
			? , ? , ? , ? , ? , ?, ? , ?
		) on duplicate key update
			hasil = ?
		";

	$ps = Jaring::$_db->prepare ($q);

	Jaring::$_db->beginTransaction ();

	foreach ($data as $m) {
		$partai_id	= (int) $m['partai_id'];
		$caleg_id	= (int) $m['caleg_id'];
		$hasil		= (int) $m['hasil'];

		$i	= 1;
		$ps->bindValue ($i++, $dapil_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $kecamatan_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $kelurahan_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $tps_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $kode_saksi, PDO::PARAM_STR);
		$ps->bindValue ($i++, $partai_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $caleg_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $hasil, PDO::PARAM_INT);
		$ps->bindValue ($i++, $hasil, PDO::PARAM_INT);
		$ps->execute ();
	}

	Jaring::$_db->commit ();
}

function inputdata_rekap_read ($table_hasil, $table_caleg)
{
	global $qwhere;

	inputdata_get_param ("GET");

	$q	="
			select	ifnull(sum(A.jumlah),0)		as jumlah
			,		ifnull(sum(A.rusak),0)		as rusak
			,		ifnull(sum(A.sisa),0)		as sisa
			,		ifnull(sum(A.sah),0)		as sah
			,		ifnull(sum(A.tidak_sah),0)	as tidak_sah
			from	". $table_hasil ."			as A
			where	1 = 1 ". $qwhere ."
		";

	$ps = Jaring::$_db->prepare ($q);
	$ps->execute ();
	$rs = $ps->fetchAll (PDO::FETCH_ASSOC);
	$ps->closeCursor ();

	return $rs;
}

function inputdata_rekap_update ($table_hasil, $table_caleg)
{
	global $dapil_id, $kecamatan_id, $kelurahan_id, $tps_id, $kode_saksi;

	inputdata_get_param ("POST");

	$jumlah			= $_POST["jumlah"];
	$rusak			= $_POST["rusak"];
	$sisa			= $_POST["sisa"];
	$sah			= $_POST["sah"];
	$tidak_sah		= $_POST["tidak_sah"];

	$q	="
		insert	into ". $table_hasil ." (
			dapil_id
		,	kecamatan_id
		,	kelurahan_id
		,	tps_id
		,	kode_saksi
		,	jumlah
		,	rusak
		,	sisa
		,	sah
		,	tidak_sah
		) values (
			? , ? , ? , ? , ? , ? , ? , ? , ? , ?
		) on duplicate key update
			jumlah		= ?
		,	rusak		= ?
		,	sisa		= ?
		,	sah			= ?
		,	tidak_sah	= ?
		";

	$ps = Jaring::$_db->prepare ($q);
	$i	= 1;
	$ps->bindValue ($i++, $dapil_id, PDO::PARAM_INT);
	$ps->bindValue ($i++, $kecamatan_id, PDO::PARAM_INT);
	$ps->bindValue ($i++, $kelurahan_id, PDO::PARAM_INT);
	$ps->bindValue ($i++, $tps_id, PDO::PARAM_INT);
	$ps->bindValue ($i++, $kode_saksi, PDO::PARAM_STR);
	$ps->bindValue ($i++, $jumlah, PDO::PARAM_INT);
	$ps->bindValue ($i++, $rusak, PDO::PARAM_INT);
	$ps->bindValue ($i++, $sisa, PDO::PARAM_INT);
	$ps->bindValue ($i++, $sah, PDO::PARAM_INT);
	$ps->bindValue ($i++, $tidak_sah, PDO::PARAM_INT);

	$ps->bindValue ($i++, $jumlah, PDO::PARAM_INT);
	$ps->bindValue ($i++, $rusak, PDO::PARAM_INT);
	$ps->bindValue ($i++, $sisa, PDO::PARAM_INT);
	$ps->bindValue ($i++, $sah, PDO::PARAM_INT);
	$ps->bindValue ($i++, $tidak_sah, PDO::PARAM_INT);

	$ps->execute ();
	$ps->closeCursor ();
}
