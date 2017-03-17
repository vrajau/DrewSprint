(function(){
	
	"use strict";
	var app_archive = Object.create(App),
		handler = function(){
			app_archive.loadApp('parts/Template/TemplateArchive.html',Database,Archive,ControllerArchive,ViewArchive);
		};

app_archive.load(handler,['assets/js/Model/Archive.js','assets/js/Controller/ControllerArchive.js','assets/js/View/ViewArchive.js']);
})();

