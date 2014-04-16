/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

function JxDashboardTopTenCaleg (id, t)
{
	this.store		= Ext.create ("Jx.Store", {
		url			:Jx.generateModDir (id)
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"nama"
		,	"hasil"
		,	"persentase"
		]
	});

	this.panel		= Ext.create ("Ext.grid.Panel", {
			id		:id
		,	title	:t
		,	store	:this.store
		,	width	:400
		,	height	:400
		,	columns	:
			[{
				header		:"Nama"
			,	dataIndex	:"nama"
			,	flex		:1
			},{
				header		:"Total Suara"
			,	dataIndex	:"hasil"
			,	xtype		:"numbercolumn"
			,	format		:"0,000"
			}]
		});

	this.doRefresh	= function ()
	{
		this.store.load ();
	};
}

function JxDashboardChartPartai (id, t)
{
	this.store		= Ext.create ("Jx.Store", {
			url			:Jx.generateModDir (id)
		,	singleApi	:false
		,	fields		:
			[
				"id"
			,	"nama"
			,	"hasil"
			,	"persentase"
			]
		});

	this.chart		= Ext.create ("Ext.chart.Chart", {
			store		:this.store
		,	animate		:true
		,	axes		:
			[{
				type		: 'Numeric'
			,	position	: 'bottom'
			,	fields		: ['persentase']
			,	label		:
				{
					renderer: Ext.util.Format.numberRenderer('00.00')
				}
			,	title		: 'Persentase'
			,	grid		: true
			,	minimum		: 0
			},{
				type		: 'Category'
			,	position	: 'left'
			,	fields		: ['nama']
			,	title		: ''
			}]
		,	series		:
			[{
				type			:"bar"
			,	axis			:"bottom"
			,	xField			:"nama"
			,	yField			:"persentase"
			,	angleField		:"persentase"
			,	showInLegend	:true
			,	tips			:
				{
					trackMouse		: true
				,	width			: 200
				,	height			: 28
				,	renderer		: function(storeItem, item)
					{
						this.setTitle(storeItem.get('hasil'));
					}
				}
			,	highlight		:true
			,	label			:
				{
					field			: 'persentase'
				,	display			: 'insideEnd'
				,	orientation		: 'horizontal'
				,	contrast		: true
				,	renderer		: Ext.util.Format.numberRenderer('00.00 %')
				,	font			: '11px Monospace'
				}
			}]
		});

	this.panel = Ext.create ("Ext.panel.Panel", {
				title	:t
			,	id		:id
			,	layout	:"fit"
			,	width	:800
			,	height	:400
			,	items	:this.chart
		});

	this.doRefresh = function ()
	{
		this.store.load ();
	};
}

function JxDashboardTop15DapilDPRD ()
{
	this.id		= "Dashboard_Dapil_Top15DPRD";
	this.dir	= Jx.generateModDir (this.id);

	this.store			= Ext.create ("Jx.Store", {
			url			:this.dir
		,	singleApi	:false
		,	groupField	:"dapil_nama"
		,	fields		:
			[
				"dapil_nama"
			,	"partai_nama"
			,	"caleg_nama"
			,	"hasil"
			]
		});

	this.panel		= Ext.create ("Ext.grid.Panel", {
			itemId	:this.id
		,	title	:"Top 15 Caleg DPRD per Dapil"
		,	width	:800
		,	autoHeight:true
		,	store	:this.store
		,	features:
			[{
				ftype			:"grouping"
			,	hideGroupHeader	:true
			}]
		,	columns	:
			[{
				header		:"Dapil"
			,	dataIndex	:"dapil_nama"
			,	width		:0
			},{
				header		:"Partai"
			,	dataIndex	:"partai_nama"
			,	width		:300
			},{
				header		:"Nama"
			,	dataIndex	:"caleg_nama"
			,	flex		:1
			},{
				header		:"Jumlah Suara"
			,	dataIndex	:"hasil"
			,	width		:140
			,	align		:"right"
			,	xtype		:"numbercolumn"
			,	format		:"0,000"
			}]
		});

	this.doRefresh = function ()
	{
		this.store.load ();
	};
}

function JXDashboardStatus ()
{
	this.id		="Dashboard_Status";
	this.dir	=Jx.generateModDir (this.id);

	this.store			= Ext.create ("Jx.Store", {
			url			:Jx.generateModDir ("Dashboard_Status")
		,	singleApi	:false
		,	fields		:
			[
				"jumlah_tps_dpr"
			,	"jumlah_tps_dprd"
			,	"jumlah_suara"
			]
		});

	this.panel			= Ext.create ("Ext.form.Panel", {
			title		:"Status"
		,	store		:this.store
		,	width		:400
		,	autoHeight	:true
		,	defaultType	:"displayfield"
		,	defaults	:
			{
				labelWidth	:140
			,	renderer	:Ext.util.Format.numberRenderer ("0,000")
			}
		,	items		:
			[{
				fieldLabel	:"Jumlah TPS DPR"
			,	name		:"jumlah_tps_dpr"
			},{
				fieldLabel	:"Jumlah TPS DPRD"
			,	name		:"jumlah_tps_dprd"
			},{
				fieldLabel	:"Jumlah Suara"
			,	name		:"jumlah_suara"
			}]
		});

	this.doRefresh = function ()
	{
		this.store.load ();
	};

	this.updateStatus = function (s, r, success)
	{
		if (!success || r.length <= 0) {
			return;
		}

		this.panel.loadRecord (r[0]);
	};

	this.store.on ("load", this.updateStatus, this);
}

function JxDashboard ()
{
	this.id		= "Dashboard";
	this.dir	= Jx.generateModDir (this.id);

	this.vStatus	= new JXDashboardStatus ();

	this.chartPartaiDPR		= new JxDashboardChartPartai (
			"Dashboard_Partai_DPR"
		,	"Total Perolehan Suara Partai dan Caleg DPR");
	this.chartPartaiDPRD	= new JxDashboardChartPartai (
			"Dashboard_Partai_DPRD"
		,	"Total Perolehan Suara Partai dan Caleg DPRD"
		);

	this.topTenCalegDPR	= new JxDashboardTopTenCaleg (
			"Dashboard_Caleg_DPR"
		,	"Top Ten Caleg DPR"
		);

	this.topTenCalegDPRD	= new JxDashboardTopTenCaleg (
			"Dashboard_Caleg_DPRD"
		,	"Top Ten Caleg DPRD"
		);

	this.top15DapilDPRD = new JxDashboardTop15DapilDPRD ();

	this.panel	= Ext.create ("Ext.panel.Panel", {
			region		:"center"
		,	margin		:"5 0 0 0"
		,	padding		:"0 5 0 5"
		,	autoScroll	:true
		,	layout		:
			{
				type			:"hbox"
			,	manageOverflow	:2
			,	reserveScrollbar:true
			}
		,	hidden		:true
		,	items		:
			[{
				xtype	:"container"
			,	layout	:"vbox"
			,	defaults:
				{
					margin	:"0 20 0 20"
				}
			,	items	:
				[
					this.vStatus.panel
				,	this.topTenCalegDPR.panel
				,	this.topTenCalegDPRD.panel
				]
			},{
				xtype	:"container"
			,	layout	:"vbox"
			,	defaults:
				{
					margin	:"0 20 20 20"
				}
			,	items	:
				[
					this.chartPartaiDPR.panel
				,	this.chartPartaiDPRD.panel
				,	this.top15DapilDPRD.panel
				]
			}]
		});

	this.hide = function ()
	{
		this.panel.hide ();
	};

	this.show = function ()
	{
		this.doRefresh ();
		this.panel.show ();
	};

	this.doRefresh = function ()
	{
		this.vStatus.doRefresh ();
		this.chartPartaiDPR.doRefresh ();
		this.chartPartaiDPRD.doRefresh ();
		this.topTenCalegDPR.doRefresh ();
		this.topTenCalegDPRD.doRefresh ();
		this.top15DapilDPRD.doRefresh ();
	};
}