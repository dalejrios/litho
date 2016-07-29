var appLitho = angular.module('liTho',['ngAnimate','ui.bootstrap','toaster']);

appLitho.controller('edicionController',function($scope,msj,graph,com){
	$scope.aut = {"estados":[{"No":0,"Et":0},{"No":1,"Et":1}],"transiciones":[]};
	$scope.nterm = {'tipo':'0'};
	$scope.nest = {'et':''};
	$scope.ntra = {'in':'','fn' : ''};

	callback = function(response){
		msj.showMessage(response);
		updAut();
	}

	$scope.newAut = function(){
		params = {'cop':'98165'};
		com.sendData(params,callback);
	}
	updAut = function(){
		com.updAutom().then(function(d){
			$scope.aut = d;
		});
	}
	$scope.delTer = function(cod){
		params = {'cop':'85973','cod':cod};
		com.sendData(params,callback);
	}
	$scope.newTer = function(tip){
		var par;
		switch(tip){
			case '1':{
				par = {'cop':'85671','cad':$scope.nterm.car,'et':$scope.nterm.et};
				break;	
			}
			case '2':{
				par = {'cop':'85672','in':$scope.nterm.ini,'fn':$scope.nterm.fin,'et':$scope.nterm.et};
				break;	
			}
			case '3':{
				par = {'cop':'85673','cad':$scope.nterm.car,'et':$scope.nterm.et};
				break;
			}
		}
		com.sendData(par,callback);
	}
	$scope.newEst = function(){
		params = {'cop' : '63227','et':$scope.nest.et}
		com.sendData(params,callback);
	}
	$scope.delEst = function(cod){
		params = {'cop' : '63228','cod' : cod};
		com.sendData(params,callback);
	}
	$scope.newTra = function(estIn,estFin,codTer){
		var params;
		if(codTer == '-1'){ //Se seleccionó cadena vacía
			params = {'cop' : '47917',
					  'ini' : estIn,
					  'fin' : estFin };
		}else{
			params = {'cop' : '47918',
					  'ini' : estIn,
					  'fin' : estFin,
					  'ter' : codTer };	
		}
		com.sendData(params,callback);
	}
	$scope.setAc = function(cod){
		params = {'cop' : '64337','cod' : cod};
		com.sendData(params,callback);
	}
	$scope.remAc = function(cod){
		params = {'cop' : '64338','cod' : cod};
		com.sendData(params,callback);	
	}
	$scope.delTra = function(ini,index,term){
		var params;
		if(term == '\u025b'){
			params = {'cop' : '39613',
					  'ini' : ini,
					  'i' : index};	
		}else{
			params = {'cop' : '39612',
					  'ini' : ini,
					  'i' : index};	
		}
		com.sendData(params,callback);
	}
	$scope.$watch('aut',function(){
		graph.graficar($scope.aut.estados,$scope.transfTrans($scope.aut.transiciones));
	});
	$scope.findState = function(cod){
		var retorno;
		angular.forEach($scope.aut.estados,function(value,key){
			if(value.No == cod){
				retorno = value.Et;
			}
		});
		return retorno;
	}
	$scope.findTerm = function(cod){
		var retorno = '\u025b';
		angular.forEach($scope.aut.terminales,function(value,key){
			if(value.cod == cod){
				retorno = value.et;	
			}
		});
		return retorno;
	}
	$scope.transfTrans = function(trans){
		var transiciones = JSON.parse(JSON.stringify(trans));
		for(i = 0; i < transiciones.length; i++){
			transiciones[i].sim = $scope.findTerm(transiciones[i].sim);
		}
		return transiciones;
	}
	$scope.test = function(){
		callback('1testing');
	}
	graph.inicializar(document.querySelector('#autEdicion'));
});