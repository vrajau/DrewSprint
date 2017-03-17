
var ControllerDashboard =(function(){
	"use strict";
 	var controller =  {
	initialize: function(model,view){
		var controller = this;
		controller.model = model;
		controller.view = view;

		controller.view.action('initialize',function(id){
			if(id !== null){
				controller.sprint(id[3]);
				controller.view.render('loading');
				controller.view.render('set-id-board',id[3]);
			}else{
				controller.view.render('error','Invalid Id');
			}
		});
	},
	
	sprint: function(id){
		var controller = this;
		controller.model.sprint(id, function(current_sprint){
			if(current_sprint){
				controller.view.render('table-current-scrum',current_sprint);
				if(current_sprint.message !== ''){
					controller.view.render('error',current_sprint.message);
				}
				controller.statusSprint(id);
			}else{
				controller.view.render('error','We could not load the data for this board');
			}
		});
	},

	statusSprint: function(id){
		var controller = this;
		controller.model.status(id,function(status){
			controller.view.render('button-sprint',status);
			
			controller.view.action('start-sprint',function(id){
				controller.startSprint(id);
			});

			controller.view.action('save-sprint',function(id){
				controller.saveSprint(id);
			});

			controller.view.action('stop-sprint',function(id){
				controller.stopSprint(id);
			});

			controller.view.action('refresh-sprint',function(id){
				controller.view.render('refresh-button');
				controller.sprint(id);
			});

			controller.weekSprint(id);

		});
	},

	startSprint: function(id){
		var controller = this;
		controller.model.startSprint(id,function(){
			controller.statusSprint(id);
			controller.weekSprint(id);
		});
	},

	saveSprint: function(id){
		var controller = this;
		controller.model.saveSprint(id,function(feedback){
			(feedback.error) ? controller.view.render('error',feedback.message) : controller.view.render('notification',feedback.message);
			controller.weekSprint(id);
		});
	},

	stopSprint: function(id){
		var controller = this;
		controller.model.stopSprint(id,function(l){
			controller.statusSprint(id);
			controller.view.render('empty-weekly-sprint');
		});
	},

	weekSprint: function(id){
		var controller = this;
		controller.model.getWeeklySprint(id,function(week_sprint){
			controller.view.render('result-weekly-sprint',week_sprint);
		});
	}



};	
	return controller;
})();
