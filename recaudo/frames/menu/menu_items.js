/*
  --- menu items --- 
  note that this structure has changed its format since previous version.
  additional third parameter is added for item scope settings.
  Now this structure is compatible with Tigra Menu GOLD.
  Format description can be found in product documentation.
*/

var MENU_ITEMS = [
	['Acometidas','cargue.php', {'tw' : 'content'},
		['Cargue Acometidas SAP','http://www.softcomplex.com/services.html', {'tw' : 'content'}],
		['Uniones','http://www.softcomplex.com/download.html', {'tw' : 'content'}],
		['Partición','http://www.softcomplex.com/order.html', {'tw' : 'content'}],
		['Asignación','http://www.softcomplex.com/order.html', {'tw' : 'content'}],
		['Descargue','http://www.softcomplex.com/support.html', {'tw' : 'content'}],
	],
	['Special Targets', null, null,
		['New Window','http://www.softcomplex.com/products/tigra_menu/', {'tw' : '_blank'}],
		['Parent Window','http://www.softcomplex.com/products/tigra_menu/', {'tw' : '_parent'}],
		['Same Frame','http://www.softcomplex.com/products/tigra_menu/', {'tw' : '_self'}],
	],
	['Another Item', null, null,
		['Level 1 Item 0','another.html', {'tw' : 'content'}],
		['Level 1 Item 1','another.html', {'tw' : 'content'}],
		['Level 1 Item 2','another.html', {'tw' : 'content'}],
		['Level 1 Item 3','another.html', {'tw' : 'content'}],
	],
	['Salir','../../', {'tw' : '_top'}],
];