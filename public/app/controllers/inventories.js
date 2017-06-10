app.controller('inventoriesController', function($scope, $http, API_URL) {	

	$scope.init = function(slug) {
		$http
			.get(API_URL + 'inventories/' + slug)
			.then(function(response) {
				console.log(response.data);
				$scope.inventories = response.data;
			});
	};
	
});