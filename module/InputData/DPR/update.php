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
	$table_hasil	= "hasil_dprd";

	$data = json_decode (file_get_contents('php://input'), true);

	foreach ($data as $m) {
		$partai_id	= (int) $m['partai_id'];
		$caleg_id	= (int) $m['caleg_id'];
		$hasil		= (int) $m['hasil'];

		$q	="	delete	from ". $table_hasil
			."	where	dapil_id		= ?"
			."	and		kecamatan_id	= ?"
			."	and		kelurahan_id	= ?"
			."	and		tps_id			= ?"
			."	and		kode_saksi		= ?"
			."	and		partai_id		= ?"
			."	and		caleg_id		= ?";

		$ps = Jaring::$_db->prepare ($q);
		$i	= 1;
		$ps->bindValue ($i++, $dapil_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $kecamatan_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $kelurahan_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $tps_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $kode_saksi, PDO::PARAM_STR);
		$ps->bindValue ($i++, $partai_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $caleg_id, PDO::PARAM_INT);
		$ps->execute ();
		$ps->closeCursor ();

		$q	="	insert	into ". $table_hasil ." ("
			."		dapil_id"
			."	,	kecamatan_id"
			."	,	kelurahan_id"
			."	,	tps_id"
			."	,	kode_saksi"
			."	,	partai_id"
			."	,	caleg_id"
			."	,	hasil"
			."	) values ( ? , ? , ? , ? , ? , ?, ? , ?)";

		$ps = Jaring::$_db->prepare ($q);
		$i	= 1;
		$ps->bindValue ($i++, $dapil_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $kecamatan_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $kelurahan_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $tps_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $kode_saksi, PDO::PARAM_STR);
		$ps->bindValue ($i++, $partai_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $caleg_id, PDO::PARAM_INT);
		$ps->bindValue ($i++, $hasil, PDO::PARAM_INT);
		$ps->execute ();
		$ps->closeCursor ();
	}

	$r['success']	= true;
	$r['data']		= Jaring::$MSG_SUCCESS_UPDATE;
} catch (Exception $e) {
	$r['data']		= $e->getMessage ();
}

require_once "../../json_end.php";
