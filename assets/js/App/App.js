var App = (function(){
	"use strict";
	var app = {
		load: function(handler,scripts){
			Promise.all(app.loadScripts(scripts))
			.then(function(scripts_loaded){
				if(app.allowLoad(scripts_loaded)){
					handler();
				}else{
					console.log('Not all script loaded');
				}
			});
		},

		loadApp: function(template_url,database,model,controller,view){
			if(this.isObject([database,model,controller,view])){
				$('#template').load(template_url,function(responseText,result){
					if(result == "success"){
						var _model = Object.create(model),
							_database = Object.create(database),
							_controller = Object.create(controller),
							_view = Object.create(view);
						_model.create(_database);
						_controller.initialize(_model,_view);
					}
				});
			}
		},

		loadScripts: function(urlScript){
			var promises = [];
			$.ajaxSetup({cache:true});
			if(Array.isArray(urlScript)){
				for(var i = 0; i < urlScript.length; i++){
				promises.push(new Promise(function(resolve,reject){
						$.getScript(urlScript[i])
						.done(function(){return resolve(true);})
						.fail(function(){return resolve(false);});
					}));
				}
			}
			return promises;
		},

		isObject: function(element){
			if(Array.isArray(element)){
				var result = true;
				for(var i = 0; i < element.length; i++){
					if(typeof element[i] !== "object" || element[i] === null){
						result = !result;
					}
				}
				return result;	
			}else{
				return;
			}
		},

		allowLoad: function(fileloaded){
			return fileloaded.reduce(function(prev,next){
				return (prev === next);
			});
		},
		
	};
	
	return app;
})();




