<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../json_begin.php";

try {
	$dapil_id		= $_POST["dapil_id"];
	$kecamatan_id	= $_POST["kecamatan_id"];
	$kelurahan_id	= $_POST["kelurahan_id"];
	$tps_id			= $_POST["tps_id"];
	$kode_saksi		= $_POST["kode_saksi"];
	$jumlah			= $_POST["jumlah"];
	$rusak			= $_POST["rusak"];
	$sisa			= $_POST["sisa"];
	$sah			= $_POST["sah"];
	$tidak_sah		= $_POST["tidak_sah"];
	$table_rekap	= "rekap_suara_dprd";

	$q	="	delete	from ". $table_rekap
		."	where	dapil_id		= ?"
		."	and		kecamatan_id	= ?"
		."	and		kelurahan_id	= ?"
		."	and		tps_id			= ?"
		."	and		kode_saksi		= ?";

	$ps = Jaring::$_db->prepare ($q);
	$i	= 1;
	$ps->bindValue ($i++, $dapil_id, PDO::PARAM_INT);
	$ps->bindValue ($i++, $kecamatan_id, PDO::PARAM_INT);
	$ps->bindValue ($i++, $kelurahan_id, PDO::PARAM_INT);
	$ps->bindValue ($i++, $tps_id, PDO::PARAM_INT);
	$ps->bindValue ($i++, $kode_saksi, PDO::PARAM_STR);
	$ps->execute ();
	$ps->closeCursor ();

	$q	="	insert	into ". $table_rekap ." ("
		."		dapil_id"
		."	,	kecamatan_id"
		."	,	kelurahan_id"
		."	,	tps_id"
		."	,	kode_saksi"
		."	,	jumlah "
		."	,	rusak "
		."	,	sisa "
		."	,	sah "
		."	,	tidak_sah "
		."	) values ( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )";

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
	$ps->execute ();
	$ps->closeCursor ();

	$r['success']	= true;
	$r['data']		= Jaring::$MSG_SUCCESS_UPDATE;
} catch (Exception $e) {
	$r['data']		= $e->getMessage ();
}

require_once "../../json_end.php";
