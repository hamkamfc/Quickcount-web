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

function JxDashboard ()
{
	this.id		= "Dashboard";
	this.dir	= Jx.generateModDir (this.id);

	this.sStatus		= Ext.create ("Jx.Store", {
			url			:Jx.generateModDir ("Dashboard_Status")
		,	singleApi	:false
		,	fields		:
			[
				"jumlah_tps"
			,	"jumlah_suara"
			]
		});

	this.vStatus	= Ext.create ("Ext.panel.Panel", {
			title	:"Status"
		,	store	:this.sStatus
		,	width	:400
		,	height	:100
		,	tpl		:new Ext.XTemplate (
					"<div> Jumlah TPS   : {jumlah_tps} </div>"
			,		"<div> Jumlah Suara : {jumlah_suara} </div>"
			)
		});

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
					this.vStatus
				,	this.topTenCalegDPR.panel
				,	this.topTenCalegDPRD.panel
				]
			},{
				xtype	:"container"
			,	layout	:"vbox"
			,	defaults:
				{
					margin	:"0 20 0 20"
				}
			,	items	:
				[
					this.chartPartaiDPR.panel
				,	this.chartPartaiDPRD.panel
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
		this.sStatus.load ();
		this.chartPartaiDPR.doRefresh ();
		this.chartPartaiDPRD.doRefresh ();
		this.topTenCalegDPR.doRefresh ();
		this.topTenCalegDPRD.doRefresh ();
	};

	this.updateStatus = function (s, r, success)
	{
		if (!success || r.length <= 0) {
			return;
		}

		this.vStatus.update ({
			jumlah_tps : r[0].get ("jumlah_tps")
		,	jumlah_suara : r[0].get ("jumlah_suara")
		});
	};

	this.sStatus.on ("load", this.updateStatus, this);
}