app
.controller('inventoriesController', function($scope, $http, $location, $filter, API_URL) {	

	$http
	.get(API_URL + 'item-status-and-code-types')
	.then(function(response) {
		$scope.itemStatus 		= response.data.status;
		$scope.itemCodeTypes 	= response.data.code_types;
	});	

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

	$scope.check = [];
	$scope.checkedAll = function() {
		var toggleStatus = $scope.isAllSelected;
		angular.forEach($scope.inventories, function(inv){ 
			if(toggleStatus)
				$scope.SelectedItems.push(inv);
			else
				$scope.SelectedItems = [];
			$scope.check[inv.id] = toggleStatus;
			$scope.check[inv.id + '-' + inv.id] = toggleStatus;
		});
		$scope.countSelectedItems = $scope.SelectedItems.length;
	}
	
	$scope.checked = function(inventory, key) {

		var count = inventory.length - 1;
	    if ($scope.check[key]) {
	    	if(count>=0) {
	    		for(x=0; x<=count; x++) {
		    		$scope.check[inventory[x].id + '-' + inventory[x].id] = true;
		    		$scope.SelectedItems.push(inventory[x]);
		    	}
	    	}
	    	else {
	    		$scope.SelectedItems.push(inventory);
	    	}

	    }
	    else {
	    	if(count>=0) {
		    	for(x=0; x<=count; x++) {
					var index = $scope.SelectedItems.indexOf(inventory[x]);
					if (index > -1) {
						$scope.SelectedItems.splice(index, 1);
					}
				}
			}
			else {
				var index = $scope.SelectedItems.indexOf(inventory);
				if (index > -1) {
					$scope.SelectedItems.splice(index, 1);
				}
			}	
	    }
	    $scope.countSelectedItems = $scope.SelectedItems.length;
	}

	$scope.sum = function(data, field) {
		var total = 0;
		angular.forEach(data, function(data) {
			total += parseInt(data[field]);
		});

		return parseInt(total);
	}

	$scope.code = function(data, type) {
		var codeType 	= $filter('filter')($scope.itemCodeTypes, { name: type });
		var match 		= $filter('filter')(data, { item_code_type_id: codeType[0].id });
		return match.reverse()[0];
	}

	$scope.transfer = function(status) {
		if(status!=null)
		{
			var active = $filter('filter')($scope.itemStatus,{ id: status });
			if($scope.countSelectedItems==1) {
				$scope.modal = {
					action	: 'change-quantity',
	            	title	: "Transfer to '"+ active[0].name +"' Item - " + $scope.SelectedItems[0].item.name + " (" + $scope.SelectedItems[0].quantity + ")",
	            	field	: { quantity: 'Quantity (only)', remarks: 'Remarks (change)' },
	            	data	: {  
	            		status 		: status, 
	            		action 		: 'change-quantity', 
	            		quantity 	: $scope.SelectedItems[0].quantity, 
	            		remarks 	: $scope.SelectedItems[0].remarks, 
	            		inventory 	: $scope.SelectedItems[0]},
	            	button	: 'Transfer'
	            };

	            $('#inventoryModal').modal('show');
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
            case 'item_code':
                $scope.modal = {
                	action: 'modify-item-code',
                	title: "Modify Item Code",
                	field: { code: 'Barcode' },
                	data: { type: type, code: $scope.code(data.item.item_codes).code, id: $scope.code(data.item.item_codes).id },
                	button: 'Save changes'
                };	
            	break;

            case 'item_price':
                $scope.modal = {
                	action: 'modify-item-price',
                	title: "Modify Market Price",
                	field: { market_price: 'Price' },
                	data: { type: type, market_price: $scope.code(data.item.item_prices).market_price, id: $scope.code(data.item.item_codes).id },
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
					}
	            });
                break;

            case 'modify-item-code':
                $http({
		            method: 'POST',
		            url: API_URL + 'inventories/update',
		            data: data
		        })
	            .then(function (response) {
					if(index !== -1){
						var itemCodes = $scope.inventories[index].item.item_codes;
						$scope.code(itemCodes).code = response.data.code;
						$('#inventoryModal').modal('hide');
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
					var index = $scope.inventories.indexOf($scope.SelectedItems[0]);
					if(response.data.quantity > 0) {
						$scope.inventories[index].quantity = parseInt(response.data.quantity);
						// $scope.inventories[index].remarks = response.data.remarks;
						$scope.check[$scope.inventories[index].id] = false;
					}
					else {
						$scope.inventories.splice(index,1);
					}

					console.log($scope.inventories[index]);
					
					$scope.SelectedItems = [];
					$scope.countSelectedItems = 0;

					$('#inventoryModal').modal('hide');
						
					
	            });
            	break;  

            default:
                break;
        }

    }
});


