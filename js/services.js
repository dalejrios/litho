//Services
appLitho = angular.module('liTho');

appLitho.service('msj',function(toaster){
	this.ErrorDesconocido = function(msg){
		toaster.pop('error','ERROR','ERROR DESCONOCIDO.\nPor favor contacte con el administrador de la aplicación. \nDescripción: ' + msg);
	}
	this.Success = function(msg){
		toaster.pop('success','EXITO!!!',msg);
	}
	this.Error = function(msg){
		toaster.pop('error','ERROR',msg);	
	}
	this.showMessage = function(data){
	if(data.substring(0,1) == '1'){
		toaster.pop('success',"Exito!!!",data.substring(1));	
	}else{
		toaster.pop('error',"Algo salio mal",data.substring(1));	
	}
}
});

appLitho.service('com',function($q,$http,msj){
	this.sendData = function(parametros,success){
		$http.post('intercept.php',parametros)
			.success(function(data){
				success(data);
			})
			.error(function(data){
				ErrorDesconocido(data);
			});
	}
	this.updAutom = function(){
		var retorno = {};
		var defer = $q.defer();
		$http.get('status.php')
			.success(function(res){
				retorno = res;
				defer.resolve(res);
			})
			.error(function(res){
				msj.ErrorDesconocido(res);
			});
		return defer.promise;
	}
});

appLitho.service('graph',function(){
	var graph;
	
	this.inicializar = function(contenedor){
		  this.graph = cytoscape({
		  container: contenedor,
		  wheelSensitivity: 0.1,
		  boxSelectionEnabled: false,
		  autounselectify: true,
		  
		  style: cytoscape.stylesheet()
			.selector('node')
			  .css({
				'content': 'data(name)',
				'text-valign': 'center',
				'color': 'white',
				'text-outline-width': 2,
				'background-color': '#D0CECE',
				'text-outline-color': 'black',
				'border-color' : 'black',
				'border-style' : 'solid',
				'border-width' : '1'
			  })
			.selector('edge')
			  .css({
				'label': 'data(label)',
				'curve-style': 'bezier',
				'target-arrow-shape': 'triangle',
				'target-arrow-color': '#ccc',
				'line-color': '#ccc',
				'width': 1
			  })
			.selector(':selected')
			  .css({
				'background-color': 'black',
				'line-color': 'black',
				'target-arrow-color': 'black',
				'source-arrow-color': 'black'
			  })
			.selector('.faded')
			  .css({
				'opacity': 0.25,
				'text-opacity': 0
			  }),
		  
		  elements: {
			nodes: [],
			edges: []
		  },
		  
		  layout: {
			name: 'grid',
			padding: 10
		  }
		});
	}
	this.graficar = function(nodos,conexiones){
		this.graph.remove(this.graph.$("edge"));
		this.graph.remove(this.graph.$("node"));
		for(i = 0; i < nodos.length; i++){
			this.graph.add({
				group:'nodes',
				data: { id: nodos[i].No, name: nodos[i].Et }
			});
			if(nodos[i].Ac){
				this.graph.style().selector('#' + nodos[i].No).style({'border-style' : 'double','border-width' : '3'}).update();
			}else{
				this.graph.style().selector('#' + nodos[i].No).style({'border-style' : 'solid','border-width' : '1'}).update();
			}
		}
		for(i = 0; i < conexiones.length; i++){
			this.graph.add({
				group:'edges',
				data: { id: "e" + i, source: conexiones[i].ini, target: conexiones[i].fin, label:' '+conexiones[i].sim}
			});
		}
		this.graph.layout({
			name: 'grid',
			padding: 10
		  });
	}
});