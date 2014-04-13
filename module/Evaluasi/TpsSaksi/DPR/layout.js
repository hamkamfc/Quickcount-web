/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

function JxEvaluasi_TpsSaksi_DPR ()
{
	this.id		= "Evaluasi_TpsSaksi_DPR";
	this.dir	= Jx.generateModDir(this.id);

	this.store		= Ext.create ("Jx.StorePaging", {
		url			:this.dir
	,	singleApi	:false
	,	fields		:
			[
				"dapil_nama"
			,	"kecamatan_nama"
			,	"kelurahan_nama"
			,	"tps_no"
			,	"tps_alamat"
			,	"kode_saksi"
			]
	});

	this.showEmptyOnly	= Ext.create ("Ext.form.field.Checkbox", {
		boxLabel		:"Tampilkan TPS yang tanpa saksi saja"
	,	value			:1
	,	uncheckedValue	:0
	,	scope			:this
	,	handler			:function (cb, s)
		{
			this.store.getProxy ().extraParams.showEmptyOnly = s;
		}
	});

	this.panel			= Ext.create ("Jx.GridPaging", {
		title			:"Evaluasi TPS - Saksi > DPR"
	,	itemId			:this.id
	,	closable		:true
	,	store			:this.store
	,	buttonBarList	:["refresh"]
	,	addButtons		:
			[
				this.showEmptyOnly
			]
	,	columns			:
		[{
			header		:"Dapil"
		,	dataIndex	:"dapil_nama"
		,	width		:180
		},{
			header		:"Kecamatan"
		,	dataIndex	:"kecamatan_nama"
		,	width		:180
		},{
			header		:"Kelurahan"
		,	dataIndex	:"kelurahan_nama"
		,	width		:180
		},{
			header		:"No. TPS"
		,	dataIndex	:"tps_no"
		,	width		:80
		},{
			header		:"Alamat TPS"
		,	dataIndex	:"tps_alamat"
		,	flex		:1
		},{
			header		:"Kode Saksi"
		,	dataIndex	:"kode_saksi"
		,	width		:100
		}]
	});

	this.doRefresh = function (perm)
	{
		this.store.getProxy ().extraParams.showEmptyOnly = this.showEmptyOnly.getValue ();
		this.store.load ();
	};
}

var Evaluasi_TpsSaksi_DPR = new JxEvaluasi_TpsSaksi_DPR();