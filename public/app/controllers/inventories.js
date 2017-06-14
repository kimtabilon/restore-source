app
.controller('inventoriesController', function($scope, $http, $location, $filter, API_URL) {	

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
		if(status!=null)
		{
			var active = $filter('filter')($scope.itemStatus,{ id: status });
			if($scope.countSelectedItems==1) {
				
				$scope.modal = {
					action: 'change-quantity',
	            	title: "Transfer to '"+ active[0].name +"' Item - " + $scope.SelectedItems[0].item.name + " (" + $scope.SelectedItems[0].quantity + ")",
	            	field: { quantity: 'Quantity (only)' },
	            	data: {  status: status, action: 'change-quantity', quantity: $scope.SelectedItems[0].quantity, inventory: $scope.SelectedItems[0]},
	            	button: 'Transfer'
	            };

	            $('#inventoryModal').modal('show');
				// $scope.modal.title = 
	            // $scope.data = { type: '', id: data.item.id ,name: data.item.name, description: data.item.description};
			}
			else {
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
		}
		$scope.selectedStatus = null;	
			
	}

	$scope.toggle = function(type, data, index) {
        $scope.type = type;
        $scope.index = index;

        switch (type) {
            case 'item':
                $scope.modal = {
                	action: 'modify-item',
                	title: "Modify Item",
                	field: { name: 'Name', description: 'Description' },
                	data: { type: type, id: data.item.id, name: data.item.name, description: data.item.description},
                	button: 'Save changes'
                };	
            	break;
            default:
                break;
        }
        $('#inventoryModal').modal('show');
    }

    $scope.update = function(action, data, index) {
        
        switch (action) {
            case 'modify-item':
                $http({
		            method: 'POST',
		            url: API_URL + 'inventories/update',
		            data: data
		        })
	            .then(function (response) {
					if(index !== -1){
						$scope.inventories[index].item = response.data;
						$('#inventoryModal').modal('hide');
						console.log(response.data);
					}
	            });
                break;

            case 'change-quantity':
            	$http({
		            method: 'POST',
		            url: API_URL + 'inventories/transferOrCreate',
		            data: data
		        })
	            .then(function (response) {
					if(index !== -1){
						var index = $scope.inventories.indexOf($scope.SelectedItems[0]);
						$scope.inventories[index].quantity = response.data;
						$scope.inventories[index].selected = false;
						$scope.SelectedItems = [];
						$scope.countSelectedItems = 0;

						$('#inventoryModal').modal('hide');
						console.log(response.data);
					}
	            });
            	break;    
            default:
                break;
        }

    }
});


