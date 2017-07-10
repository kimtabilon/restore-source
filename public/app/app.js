

// var app = angular.module('App', [], ['$httpProvider', function ($httpProvider) {
//     $httpProvider.defaults.headers.post['X-CSRF-TOKEN'] = $('meta[name=csrf-token]').attr('content');
// }]);

var app = angular.module('inventory', ['angular.filter', 'ngLoadingSpinner'], function($interpolateProvider, $locationProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
        $locationProvider.html5Mode({
		  enabled: true,
		  requireBase: false
		});


    })
	.constant('API_URL', 'http://127.0.0.1:8000/api/v1/')
	.filter( 'camelCase', function ()
	 {
	     var camelCaseFilter = function ( input )
	     {
	     	if(input)
	     	{	
	         var words = input.split( ' ' );
	         for ( var i = 0, len = words.length; i < len; i++ )
	             words[i] = words[i].charAt( 0 ).toUpperCase() + words[i].slice( 1 );
	         return words.join( ' ' );
	        } 
	     };
	     return camelCaseFilter;
	 });

      