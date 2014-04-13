/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

function JxEvaluasi_TpsSaksi_MultiSaksi ()
{
	this.id		= "Evaluasi_TpsSaksi_MultiSaksi";
	this.dir	= Jx.generateModDir(this.id);

	this.store		= Ext.create ("Jx.StorePaging", {
			url		:this.dir
		,	singleApi:false
		,	fields	:
					[
						"tps_no"
					,	"tps_alamat"
					,	"kelurahan_nama"
					,	"kecamatan_nama"
					,	"dapil_nama"
					,	"jumlah_saksi"
					]
		});

	this.panel				= Ext.create ("Jx.GridPaging", {
			itemId			:this.id
		,	title			:"Daftar TPS dengan Saksi Lebih Dari Satu"
		,	buttonBarList	:["refresh"]
		,	closable		:true
		,	store			:this.store
		,	columns			:
				[{
					header		:"Dapil"
				,	dataIndex	:"dapil_nama"
				,	width		:200
				},{
					header		:"Kecamatan"
				,	dataIndex	:"kecamatan_nama"
				,	width		:200
				},{
					header		:"Kelurahan"
				,	dataIndex	:"kelurahan_nama"
				,	width		:200
				},{
					header		:"No. TPS"
				,	dataIndex	:"tps_no"
				},{
					header		:"Alamat TPS"
				,	dataIndex	:"tps_alamat"
				,	flex		:1
				},{
					header		:"Jumlah Saksi"
				,	dataIndex	:"jumlah_saksi"
				,	width		:150
				}]
		});

	this.doRefresh	= function (perm)
	{
	};
}

var Evaluasi_TpsSaksi_MultiSaksi = new JxEvaluasi_TpsSaksi_MultiSaksi();