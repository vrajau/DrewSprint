var Dashboard = (function(){
	"use strict";
		var dashboard = {
			create: function(database){
				this.database = database;
			},

			sprint: function(id,handler){
				this.database.sprint(id,handler);
			},

			status: function(id,handler){
				this.database.status(id,handler);
			},

			startSprint: function(id,handler){
				this.database.startSprint(id,handler);
			},

			saveSprint: function(id,handler){
				this.database.saveSprint(id,handler);
			},

			stopSprint: function(id,handler){
				this.database.stopSprint(id,handler);
			},

			getWeeklySprint: function(id,handler){
				this.database.weeklySprint(id,handler);
			}

		};
		return dashboard;
})();
