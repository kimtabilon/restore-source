var app = angular.module('inventory', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    }).constant('API_URL', 'http://127.0.0.1:8000/api/v1/');

      