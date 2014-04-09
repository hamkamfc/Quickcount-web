<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../json_begin.php";

try {
	$type			= $_POST["type"];
	$dapil_id		= $_POST["dapil_id"];
	$kecamatan_id	= $_POST["kecamatan_id"];
	$kelurahan_id	= $_POST["kelurahan_id"];
	$tps_id			= $_POST["tps_id"];
	$kode_saksi		= $_POST["kode_saksi"];

	$q	="	delete	from saksi_default "
		."	where	type			= ?"
		."	and		dapil_id		= ?"
		."	and		kecamatan_id	= ?"
		."	and		kelurahan_id	= ?"
		."	and		tps_id			= ?";

	$ps = Jaring::$_db->prepare ($q);
	$i	= 1;
	$ps->bindValue ($i++, $type, PDO::PARAM_INT);
	$ps->bindValue ($i++, $dapil_id, PDO::PARAM_INT);
	$ps->bindValue ($i++, $kecamatan_id, PDO::PARAM_INT);
	$ps->bindValue ($i++, $kelurahan_id, PDO::PARAM_INT);
	$ps->bindValue ($i++, $tps_id, PDO::PARAM_INT);
	$ps->execute ();
	$ps->closeCursor ();

	$q	="	insert	into saksi_default ("
		."		type"
		."	,	dapil_id"
		."	,	kecamatan_id"
		."	,	kelurahan_id"
		."	,	tps_id"
		."	,	kode_saksi"
		."	) values ( ? , ? , ? , ? , ? , ? )";

		$ps = Jaring::$_db->prepare ($q);
		$i	= 1;
		$ps->bindValue ($i++, $type, PDO::PARAM_INT);
		$ps->bindValue ($i++, $dapil_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $kecamatan_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $kelurahan_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $tps_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $kode_saksi, PDO::PARAM_STR);
		$ps->execute ();
		$ps->closeCursor ();

	$r['success']	= true;
	$r['data']		= Jaring::$MSG_SUCCESS_UPDATE;
} catch (Exception $e) {
	$r['data']		= $e->getMessage ();
}

require_once "../json_end.php";