var ViewArchive = (function (){
	"use strict";
	var view = {
		elements:{
			archive: $('#archive'),
			delete: 'delete-archive'
			
		},

		template:{
			archive:'#archive-template' 
		},

		events: {
			choose: function(handler){
				handler(document.location.search.match(/(\?|\&)([^=]+)\=([^&]+)/));
			},
			delete: function(handler){
				$('.'+view.elements.delete).on('click',function(event){
					event.preventDefault();
					var value = $(this).prop('id'),
						this_delete = $(this) ;
					alertify.confirm('Are you sure you want to delete this week',function(answer){
						if(answer){
							this_delete.closest('.week-card-container').fadeOut(400,function(){
								handler(value);
							});		
						}
					});
				});
			}
		},

		display:{
			appendTemplate: function(template,data,element){
				var template_compiler = Handlebars.compile($(template).html()),
					html =  template_compiler({data:data});
				element.empty();	
				element.append(html);
			}
		},

		action: function(name_event,handler){
			switch(name_event){
				case 'load-board':
					view.events.choose(handler);
				break;
				case 'delete':
					view.events.delete(handler);
				break;
			}
				
		},

		render: function(to_render,parameters){
			var view = this;
			switch(to_render){
				case 'archive':	
					view.display.appendTemplate(view.template.archive,parameters,view.elements.archive);
				break;
			}	
		}
	};
	return view;
})();