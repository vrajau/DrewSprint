var Archive = (function(){
	"use strict";
		var archive= {
			create: function(database){
				this.database = database;
			},

			boards: function(handler){
				this.database.getBoards(handler);
			},

			archive: function(id,handler){
				this.database.getArchive(id,handler);
			},

			delete: function(idWeek,idBoard,handler){
				this.database.deleteArchive(idWeek,idBoard,handler);
			}

		};
		return archive;
})();