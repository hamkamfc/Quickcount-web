function JxImport_HasilDPRD ()
{
	this.id			= "Import_HasilDPRD";
	this.dir		= Jx.generateModDir (this.id);
	this.addButtons	= [];

	this.store		= Ext.create ("Jx.StorePaging", {
		url			:this.dir
	,	singleApi	:false
	,	fields		:
		[
			"filename"
		,	"_when"
		]
	});

	this.storeImport	= Ext.create ("Ext.data.Store", {
		data			:[]
	,	fields			:
		[
			"id"
		,	"filename"
		,	"status"
		]
	});

	this.gridUpload	= Ext.create ("Ext.grid.Panel",
	{
		region		:"east"
	,	split		:true
	,	width		:400
	,	store		:this.storeImport
	,	tbar		:
		[{
			xtype		:"button"
		,	text		:"Add"
		,	id			:"import"
		,	iconCls		:"add"
		},"->",{
			xtype		:"button"
		,	text		:"Upload"
		,	id			:"start-upload"
		,	scope		:this
		,	handler		:function (b)
			{
				Import_HasilDPRD.uploader.start ();
			}
		}]
	,	bbar		:
		[{
			xtype		:"button"
		,	text		:"Clear"
		,	scope		:this
		,	handler	:function (b)
			{
				this.storeImport.loadData ([]);
			}
		}]
	,	columns		:
		[{
			header		:"Filename"
		,	dataIndex	:"filename"
		,	flex		:1
		},{
			header		:"Status"
		,	dataIndex	:"status"
		}]
	});

	this.grid			= Ext.create ("Jx.GridPaging", {
		store			:this.store
	,	buttonBarList	:["refresh"]
	,	region			:"center"
	,	addButtons		:
		[
		]
	,	columns			:
		[{
			header			:"Filename"
		,	dataIndex		:"filename"
		,	flex			:1
		},{
			header			:"Upload Time"
		,	dataIndex		:"_when"
		,	width			:200
		}]
	});

	this.panel	= Ext.create ("Ext.container.Container",
		{
			itemId		:this.id
		,	title		:"Import > DPRD"
		,	closable	:true
		,	layout		:"border"
		,	items		:
			[
				this.grid
			,	this.gridUpload
			]
		});

	this.doRefresh	= function (perm)
	{
		this.grid.doRefresh (perm);
	};

	this.panel.on ("boxready"
		, function (com, width, height, e)
		{
			this.uploader = new plupload.Uploader ({
					browse_button	:"import"
				,	url				: "../../Import/HasilDPRD/upload.php"
				});

			this.uploader.init ();

			this.uploader.bind ("FilesAdded", this.bImportClick);
			this.uploader.bind ("UploadProgress", this.uploadProgress);
			this.uploader.bind ("Error", this.uploadError);
		}
		, this
	);

	this.bImportClick	= function (up, files)
	{
		plupload.each (
				files
			,	function (file)
				{
					Import_HasilDPRD.storeImport.add ({ "id" : file.id, "filename" : file.name, "status" : "0 %" });
				}
		);
	};

	this.uploadProgress = function (up, file)
	{
		var i = Import_HasilDPRD.storeImport.findExact ("id", file.id);

		if (i >= 0) {
			var g = Import_HasilDPRD.gridUpload;

			var s = g.getStore ();

			s.getAt (i).set ("status", file.percent +" %");
			s.commitChanges ();

			g.getView ().refresh ();
		}
	};

	this.uploadError = function (up, err)
	{
		Jx.msg.error (err.code +":"+ err.message);
	};
}

var Import_HasilDPRD = new JxImport_HasilDPRD ();
