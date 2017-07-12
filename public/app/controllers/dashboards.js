app
.controller('dashboardController', function ($scope, $http, $location, $filter, API_URL) {

	$http
    .get(API_URL + 'dashboard/')
    .then(function (response) {
        $scope.users = response.data.users;
        $scope.items = response.data.items;
        $scope.inventories = response.data.inventories;
        console.log($scope.inventories);
        $scope.donors = response.data.donors;
    	$scope.itemStatus = response.data.itemStatus;
    });

    
    
});


