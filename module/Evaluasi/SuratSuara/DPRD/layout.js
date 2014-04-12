/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

function JxEvaluasi_SuratSuara_DPRD ()
{
	this.id		= "Evaluasi_SuratSuara_DPRD"
	this.dir	= Jx.generateModDir (this.id);

	this.sDapil		= Ext.create ("Jx.StorePaging", {
		url			:Jx.generateModDir ("Wilayah_Dapil")
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"nama"
		]
	});

	this.cbDapil		= Ext.create ("Jx.ComboPaging", {
		store			:this.sDapil
	,	name			:"dapil_id"
	,	fieldLabel		:"Dapil"
	,	valueField		:"id"
	,	displayField	:"nama"
	});

	this.sKecamatan = Ext.create ("Jx.StorePaging", {
		url			:Jx.generateModDir ("Wilayah_Kecamatan")
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"nama"
		]
	});

	this.cbKecamatan	= Ext.create ("Jx.ComboPaging", {
		store			:this.sKecamatan
	,	name			:"kecamatan_id"
	,	fieldLabel		:"Kecamatan"
	,	valueField		:"id"
	,	displayField	:"nama"
	});

	this.sKelurahan = Ext.create ("Jx.StorePaging", {
		url			:Jx.generateModDir ("Wilayah_Kelurahan")
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"nama"
		]
	});

	this.cbKelurahan	= Ext.create ("Jx.ComboPaging", {
		store			:this.sKelurahan
	,	name			:"kelurahan_id"
	,	fieldLabel		:"Kelurahan"
	,	valueField		:"id"
	,	displayField	:"nama"
	});

	this.bReload	= Ext.create ("Ext.button.Button", {
			text	:"Muat ulang"
		});

	this.form	= Ext.create ("Ext.form.Panel", {
		title	:"Filter Data"
	,	region	:"west"
	,	width	:"35%"
	,	split	:true
	,	defaults:
		{
			width		:400
		,	labelAlign	:"right"
		}
	,	items	:
		[
			this.cbDapil
		,	this.cbKecamatan
		,	this.cbKelurahan
		]
	,	buttons	:
		["->"
		,	this.bReload
		,"->"
		]
	});

	this.store	= Ext.create ("Jx.Store", {
		url		:this.dir
	,	fields	:
		[
			"tps_no"
		,	"tps_alamat"
		,	"jumlah"
		,	"rusak"
		,	"sisa"
		,	"sah"
		,	"tidak_sah"
		]
	});

	this.grid	= Ext.create ("Ext.grid.Panel", {
		title	:"Surat Suara"
	,	region	:"center"
	,	store	:this.store
	,	columns	:
		[{
			header		:"No. TPS"
		,	dataIndex	:"tps_no"
		,	width		:100
		},{
			header		:"Alamat TPS"
		,	dataIndex	:"tps_alamat"
		,	flex		:1
		},{
			header		:"Surat Suara"
		,	columns		:
			[{
				header		:"Jumlah"
			,	dataIndex	:"jumlah"
			},{
				header		:"Rusak"
			,	dataIndex	:"rusak"
			},{
				header		:"Sisa"
			,	dataIndex	:"sisa"
			},{
				header		:"Sah"
			,	dataIndex	:"sah"
			},{
				header		:"Tidak Sah"
			,	dataIndex	:"tidak_sah"
			}]
		}]
	});

	this.panel	= Ext.create ("Ext.container.Container", {
		title	:"Validasi > Surat Suara > DPRD"
	,	closable:true
	,	layout	:"border"
	,	items	:
		[
			this.form
		,	this.grid
		]
	});

	this.cbDapilOnSelect = function (cb, r, e)
	{
		this.sKecamatan.clearFilter (true);
		this.sKecamatan.filter ("dapil_id", r[0].get("id"));
		this.sKelurahan.loadData ([]);
	};

	this.cbKecamatanOnSelect = function (cb, r, e)
	{
		this.sKelurahan.clearFilter (true);
		this.sKelurahan.filter ("kecamatan_id", r[0].get("id"));
	};

	this.cbKelurahanOnSelect = function (cb, r, e)
	{
	};

	this.doRefresh = function (perm)
	{
		this.sDapil.load ();
	};

	this.reloadGrid = function (b)
	{
		this.store.getProxy ().extraParams = {
			dapil_id		: this.cbDapil.getValue ()
		,	kecamatan_id	: this.cbKecamatan.getValue ()
		,	kelurahan_id	: this.cbKelurahan.getValue ()
		};

		this.store.load ();
	};

	this.bReload.on ("click", this.reloadGrid, this);

	this.cbDapil.on ("select", this.cbDapilOnSelect, this);
	this.cbKecamatan.on ("select", this.cbKecamatanOnSelect, this);
	this.cbKelurahan.on ("select", this.cbKelurahanOnSelect, this);
}

var Evaluasi_SuratSuara_DPRD = new JxEvaluasi_SuratSuara_DPRD ();