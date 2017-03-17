var ControllerMain =(function(){
	"use strict";
 	var controller =  {
	initialize: function(model,view){
		var controller = this;
		controller.model = model;
		controller.view = view;
		
		controller.view.action('initialize',function(){
			controller.getBoards();
		});
	},

	getBoards: function(){
		var controller = this;
		controller.model.boards(function(boards){
			controller.view.render('boards',boards);
			
			controller.view.action('clickSettings',function(id){
				controller.settings(id);
			});

			controller.view.action('clickAdd',function(link){
				controller.addBoard(link);
			});

		});
	},

	addBoard: function(link){
		var controller = this;
		controller.model.addBoard(link,function(message){
			if(message === null){
				controller.getBoards();
			}else{
				controller.view.render('error',message);
			}
			
		});
	},

	settings: function(id){
		var controller = this;
		controller.model.settings(id,function(settings){
			if(typeof settings.error !== 'undefined' ){
				controller.view.render('alert',settings.error);
			}else{
				controller.view.render('modal-settings',settings);
				controller.view.action('saveAddition',function(label){
					var additions = {board:id,label:label};
					controller.updateSettings('addition',additions);	
				});

				controller.view.action('saveInclude',function(list){
					var include = {board:id,list:list};
					controller.updateSettings('include',include);
				});

				controller.view.action('saveStats',function(label){
					var stats = {board:id,label:label};
					console.log(stats);
					//controller.updateStats(stats);
				});
			}
		});
	},

	updateSettings: function(type,data){
		var controller = this;
		var feedback = function(result){
			controller.view.render('update-setting',{id:data.label,'message':feedback.message,'type':feedback.type});
		};
		switch(type){
			case 'addition':
				controller.model.updateAddition(data,feedback);
			break;
			case 'include':
				controller.model.updateInclude(data,feedback);
			break;
			case 'stats':
				controller.model.updateStats(data,feedback);
			break;
		}
	},

	



	

};	
	return controller;
})();
