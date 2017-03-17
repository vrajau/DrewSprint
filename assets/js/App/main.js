(function(){
	"use strict";
	var app_settings = Object.create(App),
		handler = function(){
			app_settings.loadApp('parts/Template/TemplateMain.html',Database,Main,ControllerMain,ViewMain);
		};

	app_settings.load(handler,['assets/js/View/ViewMain.js','assets/js/Model/Main.js','assets/js/Controller/ControllerMain.js']);


})();
