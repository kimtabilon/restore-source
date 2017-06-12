app
.controller('inventoriesController', function($scope, $http, $location, $filter, API_URL) {	
	// ITEM STATUS
	$scope.selectedStatus = '';

	$http
	.get(API_URL + 'item-status')
	.then(function(response) {
		$scope.itemStatus = response.data;
		// console.log(response.data);
	});	

	// ITEMS
	$scope.$on('$locationChangeSuccess', function(event, newUrl, oldUrl){
	    var status = window.location.hash.substr(1);
	    $http
			.get(API_URL + 'inventories/' + status)
			.then(function(response) {
				$scope.inventories = response.data;
				$scope.SelectedItems = [];
				$scope.countSelectedItems = 0;
			});
		$scope.status = status;	
	});

	$scope.checkedAll = function() {
		var toggleStatus = $scope.isAllSelected;
		angular.forEach($scope.inventories, function(inv){ 
			if(toggleStatus)
				$scope.SelectedItems.push(inv);
			else
				$scope.SelectedItems = [];
			inv.selected = toggleStatus; 
		});
		// console.log($scope.SelectedItems);
		$scope.countSelectedItems = $scope.SelectedItems.length;
		
	}

	$scope.checked = function(inventory) {
	    if (inventory.selected) {
	        $scope.SelectedItems.push(inventory);
	        // $scope.isAllSelected = true;
	    }
	    else {
	      var index = $scope.SelectedItems.indexOf(inventory);
	      if (index > -1) {
	        $scope.SelectedItems.splice(index, 1);
	      }
	    }
	    // $scope.isAllSelected = $scope.inventories.every(function(inv){ return inv.selected; })
	    // console.log($scope.SelectedItems)  //array of selected items
	    $scope.countSelectedItems = $scope.SelectedItems.length;
	}

	$scope.transfer = function(status) {
		var active = $filter('filter')($scope.itemStatus,{ id: status });
		if (confirm($scope.countSelectedItems + " item/s will be moved to status '" + active[0].name + "'. Continue?")) {
	        $http({
	            method: 'POST',
	            url: API_URL + 'inventories/transfer/' + status,
	            data: $scope.SelectedItems
	        })
            .then(function (response) {
				console.log(response.data);
            });

	        angular.forEach($scope.SelectedItems, function(item){ 
	        	var index = $scope.inventories.indexOf(item);
				$scope.inventories.splice(index,1);
			});
			$scope.countSelectedItems = 0;
	        $scope.SelectedItems = [];
	    }
	    else {

	    }
	}

	$scope.toggle = function(type, data, index) {
        $scope.type = type;
        $scope.index = index;

        switch (type) {
            case 'item':
                $scope.form_title = "Modify Item";
                $scope.data = { type: type, id: data.item.id ,name: data.item.name, description: data.item.description};
            	break;
            default:
                break;
        }
        $('#editItemModal').modal('show');
    }

    $scope.update = function(type, data, index) {
        
        switch (type) {
            case 'item':
                $http({
		            method: 'POST',
		            url: API_URL + 'inventories',
		            data: data
		        })
	            .then(function (response) {
					if(index !== -1){
						
						$scope.inventories[index].item = response.data;
						$('#editItemModal').modal('hide');
						console.log(response.data);
					}
	            });
                break;
            default:
                break;
        }

    }
});


