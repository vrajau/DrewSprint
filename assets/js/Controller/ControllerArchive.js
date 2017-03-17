var ControllerArchive =(function(){
	"use strict";
 	var controller =  {
	initialize: function(model,view){
		var controller = this;
		controller.model = model;
		controller.view = view;
		controller.view.action('load-board',function(id){
			if(id !== null){
				controller.getArchive(id[3]);
			}else{
				controller.view.render('undefined-data','Invalid Id');
			}
		});
	},

	getArchive: function(id){
		var controller = this;
		controller.model.archive(id,function(archives){
			controller.view.render('archive',archives);
			controller.view.action('delete',function(idWeek){
					controller.delete(idWeek,id);
			});
		});
	},

	delete: function(week,board){
		var controller = this;
		controller.model.delete(week,board,function(){
			controller.getArchive(board);
		});
	}
};	
	return controller;
})();
