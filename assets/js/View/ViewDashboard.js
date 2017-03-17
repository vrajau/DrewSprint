var ViewDashboard = (function (){
	"use strict";
	var view = {
		elements:{
			dropdown_container: $('#dropdown'),
			dropdown: '#select-boards',
			live: $('#live-table'),
			progression: $('#live-progression'),
			day_progression: $('#day-to-day-progression'),
			control_sprint: $('#control-sprint'),
			error: $('#error'),
			start: '#start',
			save: '#save',
			stop: '#stop',
			refresh: '#refresh',
			board: '#id-board',

		},
		template:{
			dropdown: '#dropdown-board-template',
			current_scrum: '#current-scrum-template',
			control_sprint: '#control-sprint-template',
			weeklySprint: '#weekly-sprint-template',
			error: '#error-template'
		},

		events:{
			choose: function(handler){
				handler(document.location.search.match(/(\?|\&)([^=]+)\=([^&]+)/));
			},
			panel: function(handler,element){
				$(element).on('click',function(){
					$(this).blur();
					handler($(view.elements.board).val());
				});
			},
		},

		display:{
			appendTemplate: function(template,data,element){
				var template_compiler = Handlebars.compile($(template).html()),
					html =  template_compiler({data:data});
				element.empty();	
				element.append(html);
			},

			spin: function(element){
				var spinner = Ladda.create($('#refresh')[0]);
				spinner.start();
			},

			error: function(text){
				alertify.error(text);
			},

			notification: function(text){
				alertify.log(text);
			}
		},

		action: function(name_event,handler){
			switch(name_event){
				case 'initialize':
					view.events.choose(handler);
				break;
				case 'start-sprint':
					view.events.panel(handler,view.elements.start);
				break;
				case 'save-sprint':
					view.events.panel(handler,view.elements.save);
				break;
				case 'stop-sprint':
					view.events.panel(handler,view.elements.stop);
				break;
				case 'refresh-sprint':
					view.events.panel(handler,view.elements.refresh);
				break;
			}
				
		},

		render: function(to_render,parameters){
			switch(to_render){
				case 'table-current-scrum':
					view.display.appendTemplate(view.template.current_scrum,parameters,view.elements.live);
				break;
				case 'button-sprint':
					view.display.appendTemplate(view.template.control_sprint,parameters,view.elements.control_sprint);
				break;
				case 'set-id-board':
					$('#id-board').val(parameters);
				break;
				case 'result-weekly-sprint':
					view.display.appendTemplate(view.template.weeklySprint,parameters,view.elements.day_progression);
				break;
				case 'empty-weekly-sprint':
					$(view.elements.day_progression).empty();
				break;
				case 'notification':
					view.display.notification(parameters);
				break;
				case 'error':
					view.display.error(parameters);
				break;
				case 'loading':
					view.elements.live.append('<img src="assets/images/loader.gif" id="loading"/>');
				break;
				case 'refresh-button':
					view.display.spin(view.elements.refresh);
				break;
			}
		}
	};
	return view;
})();