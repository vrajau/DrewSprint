var Database = (function(){
	"use strict";

	var db = {
		path: {
			update: './sprint/post/',
			get: 	'./sprint/get/'
		},
		type: ['GET','POST'],


		request: function(options,handler,file,type_action){
			options = (typeof options == 'object' && options !== null)? options : {};
			type_action = (this.type.indexOf(type_action) == -1)? ''  : type_action;
			options.method = type_action;
			options.url = (type_action === '')? '' : (type_action === 'GET')? this.path.get+file : this.path.update+file;
			$.ajax(options).done(handler);		
		},

		boards: function(handler){
			db.request({dataType:"json"},handler,'boards.php','GET');
		},

		getSettings: function(data,handler){
			db.request({dataType:"json",data:{id:data}},handler,'settings.php','GET');
		},

		getArchive: function(id_board,handler){
			this.request({dataType:"json",data:{id:id_board}},handler,'archives.php','GET');
		},

		sprint: function(id_board,handler){
			db.request({dataType:"json",data:{id:id_board}},handler,'sprint.php','GET');
		},

		status: function(id_board,handler){
			db.request({dataType:"json",data:{id:id_board}},handler,'status.php','GET');
		},

		weeklySprint: function(id_board,handler){
			db.request({dataType:"json",data:{id:id_board}},handler,'weekly-result.php','GET');
		},

		addBoard: function(link,handler){
			db.request({data:{url:link},dataType:"json"},handler,'addboard.php','POST');
		},

		updateAddition: function(data,handler){
			db.request({data:{addition:data}},handler,'addition.php','POST');
		},

		updateInclude: function(data,handler){
			db.request({data:{included:data}},handler,'included.php','POST');
		},

		startSprint: function(id_board,handler){
			db.request({data:{id:id_board}},handler,'start.php','POST');
		},

		saveSprint: function(id_board,handler){
			db.request({data:{id:id_board}},handler,'save.php','POST');
		},

		stopSprint: function(id_board,handler){
			db.request({data:{id:id_board}},handler,'stop.php','POST');
		},

		deleteArchive: function(idWeek,idBoard,handler){
			db.request({data:{week:idWeek,board:idBoard}},handler,'delete-archive.php','POST');
		}	
	};

	return db;
})();










