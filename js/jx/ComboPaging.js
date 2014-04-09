/*
	Copyright 2013 x10c-lab.com
	Authors:
		- mhd.sulhan (sulhan@x10c-lab.com)

	Custom combobox with paging and searching.
*/
Ext.define ("Jx.ComboPaging", {
	extend			:"Ext.form.field.ComboBox"
,	alias			:"jx.combopaging"
,	forceSelection	:true
,	pageSize		:Jx.pageSize
,	shrinkWrap		:3
,	typeAhead		:true
,	typeAheadDelay	:500
,	queryMode		:"remote"
,	listConfig		:
	{
		loadingText		:"Loading ..."
	,	emptyText		:"Data not found."
	}

,	initComponent	:function ()
	{
		this.callParent (arguments);
	}
});
