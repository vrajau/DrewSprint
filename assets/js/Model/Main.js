var Main = (function(){
	"use strict";
		var main = {
			create: function(database){
				main.database = database;
			},

			boards: function(handler){
				main.database.boards(handler);
			},

			addBoard: function(url,handler){
				main.database.addBoard(url,handler);
			},

			update: function(data,handler){
				main.database.addBoard(data,handler);
			},
		
			settings: function(id,handler){
				main.database.getSettings(id,handler);
			},
		
			updateAddition: function(data,handler){
				main.database.updateAddition(data,handler);
			},

			updateInclude: function(data,handler){
				main.database.updateInclude(data,handler);
			}
		};
		return main;
})();