(function(){
	"use strict";
	var app_dashboard = Object.create(App),
		handler = function(){
			app_dashboard.loadApp('parts/Template/TemplateDashboard.html',Database,Dashboard,ControllerDashboard,ViewDashboard);
		};

app_dashboard.load(handler,['assets/js/Model/Dashboard.js','assets/js/Controller/ControllerDashboard.js','assets/js/View/ViewDashboard.js']);
})();

