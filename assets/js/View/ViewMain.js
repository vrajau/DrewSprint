var ViewMain = (function(){
	"use strict";
	var view = {
		elements:{
			boards: $('#boards'),
			options: $('#settings'),
			settings: '.settings',
			button_addboard: '#add-board',
			url: '#shortlink'
		},

		template:{
			boards: '#boards',
			modal: '#settings-template'
		},

		events: {
			openSettings: function(handler){
				var that = view;
				$(that.elements.settings).on('click',function(){
					handler($(this).closest('.boards-tile').prop('id'));
				});
			},

			saveAddition: function(handler){
				var that = view;
				$('.additions').on('change',function(){
					handler($(this).val());
				}); 
			},

			saveInclude: function(handler){
				var that = view;
				$('.include').on('change',function(){
					handler($(this).val());
				}); 
			},

			saveStats: function(handler){
				var that = view;
				$('.stats').on('change',function(){
					handler($(this).val());
				});
			},

			addBoard: function(handler){
 				var that = view;
				$(that.elements.button_addboard).on('click',function(){
					$(this).blur();
					handler($(that.elements.url).val());
				});
			}
		},

		display:{
			appendTemplate: function(template,data,element){
				var template_compiler = Handlebars.compile($(template).html()),
					html =  template_compiler({data:data});
				element.empty();	
				element.append(html);
			},

			boardsView: function(template,data,element){
				this.appendTemplate(template,data,element);
				for(var i = 0,len = data.length; i < len ; i++){
					if(data[i].color === null){
						$('#'+data[i].id).css({
							'background' : 'linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),url('+data[i].bgrUrl+')',
							'background-position': 'center center',
							'background-size': 'cover'
						});
					}else{
						$('#'+data[i].id).css('background-color',data[i].color);
					}
				}
			},

			error: function(text){
				alertify.error(text);
			},

			alert: function(text){
				alertify.alert(text);
			}
		},

		action: function(name_event,handler){
			var view = this;
			switch(name_event){
				case 'initialize':
					$(handler);
				break;
				case 'clickAdd':
					view.events.addBoard(handler);
				break;
				case 'clickSettings':
					view.events.openSettings(handler);
				break;
				case 'saveInclude':
					view.events.saveInclude(handler);
				break;
				case 'saveAddition':
					view.events.saveAddition(handler);
				break;
				case 'saveStats':
					view.events.saveStats(handler);
				break;
			}	
		},

		render: function(to_render,parameters){
			var view = this;
			switch(to_render){
				case 'boards':
					view.display.boardsView(view.template.boards,parameters,view.elements.boards);
				break;
				case 'modal-settings':
					view.display.appendTemplate(view.template.modal,parameters,view.elements.options);
					$('#modal-settings').modal('show');
				break;
				case 'error':
					view.display.error(parameters);
				break;
				case 'alert':
					view.display.alert(parameters);
				break;	
			}	
		}
	};
	return view;
	
})();