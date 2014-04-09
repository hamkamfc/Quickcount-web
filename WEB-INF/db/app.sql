create table imported
(
	type		integer -- 1 : Hasil DPR, 2: Hasil DPRD, 3: Rekap DPR, 4: Rekap DPRD
,	filename	varchar(256)
,	_when		timestamp
);

insert into jaring._menu (
	id	,pid	,type	,label		,icon	,image	,module		, description
) values (
	10	,0		,1		,'Import'	,'menu'	,''		,'Import'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,10		,4);

insert into jaring._menu (
	id	,pid	,type	,label					,icon	,image	,module				, description
) values (
	11	,10		,1		,'Hasil Pemilu DPR'		,'menu'	,''		,'Import_HasilDPR'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,11		,4);

insert into jaring._menu (
	id	,pid	,type	,label					,icon	,image	,module				, description
) values (
	12	,10		,1		,'Hasil Pemilu DPRD'	,'menu'	,''		,'Import_HasilDPRD'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,12		,4);

insert into jaring._menu (
	id	,pid	,type	,label				,icon	,image	,module				, description
) values (
	13	,10		,1		,'Surat Suara DPR'	,'menu'	,''		,'Import_SuratSuaraDPR'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,13		,4);

insert into jaring._menu (
	id	,pid	,type	,label				,icon	,image	,module				, description
) values (
	14	,10		,1		,'Surat Suara DPRD'	,'menu'	,''		,'Import_SuratSuaraDPRD'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,14		,4);

insert into jaring._menu (
	id	,pid	,type	,label				,icon	,image	,module				, description
) values (
	20	,0		,1		,'Hasil Pemilu'		,'menu'	,''		,'HasilPemilu'		,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,20		,4);

insert into jaring._menu (
	id	,pid	,type	,label		,icon	,image	,module				, description
) values (
	21	,20		,1		,'DPR'		,'menu'	,''		,'HasilPemilu_DPR'		,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,21		,4);

insert into jaring._menu (
	id	,pid	,type	,label		,icon	,image	,module				, description
) values (
	22	,20		,1		,'DPRD'		,'menu'	,''		,'HasilPemilu_DPRD'		,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,22		,4);

drop table saksi_default;

create table saksi_default (
	type			integer
,	dapil_id		integer
,	kecamatan_id	integer
,	kelurahan_id	integer
,	tps_id			integer
,	kode_saksi		varchar(64)
);

insert into jaring._menu (
	id	,pid	,type	,label		,icon	,image	,module				, description
) values (
	30	,0		,1		,'Evaluasi'	,'menu'	,''		,'Evaluasi'			,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,30		,4);

insert into jaring._menu (
	id	,pid	,type	,label			,icon	,image	,module					, description
) values (
	31	,30		,1		,'TPS - Saksi'	,'menu'	,''		,'Evaluasi_TpsSaksi'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,31		,4);

insert into jaring._menu (
	id	,pid	,type	,label			,icon	,image	,module					, description
) values (
	32	,31		,1		,'TPS - Saksi - DPR'	,'menu'	,''		,'Evaluasi_TpsSaksi_DPR'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1 ,32	,4);

insert into jaring._menu (
	id	,pid	,type	,label			,icon	,image	,module					, description
) values (
	33	,31		,1		,'TPS - Saksi - DPRD'	,'menu'	,''		,'Evaluasi_TpsSaksi_DPRD'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1 ,33	,4);
