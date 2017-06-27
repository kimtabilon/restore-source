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
				$scope.inventories 			= response.data;
				$scope.SelectedItems 		= [];
				$scope.countSelectedItems 	= 0;
				$scope.checkParent 			= [];
				$scope.checkChild 			= [];
				
			});
		$scope.status = status;	
	});

	$scope.checkedAll = function() {
		var toggleStatus = $scope.isAllSelected;
		angular.forEach($scope.inventories, function(inv, key){ 
			if(toggleStatus) {
				$scope.SelectedItems.push(inv);
			}
			else {
				$scope.SelectedItems = [];
			}
			$scope.checkParent[inv.id] 	= toggleStatus;
			$scope.checkChild[inv.id] 	= toggleStatus;
		});
		$scope.countSelectedItems = $scope.SelectedItems.length;
	}
	
	$scope.checked = function(inventory) {

		var count = inventory.length - 1;
		if(count>=0) {
			for(x=0; x<=count; x++) {
				var invID = inventory[x].id;

				if ($scope.checkParent[inventory[0].id]) {
					$scope.checkParent[invID] 	= true;
					$scope.checkChild[invID] 	= true;
		    		$scope.SelectedItems.push(inventory[x]);
		    	}
		    	else {
		    		var foundIndex 				= $scope.SelectedItems.indexOf(inventory[x]);
		    		$scope.checkParent[invID] 	= false;
		    		$scope.checkChild[invID] 	= false;

					$scope.SelectedItems.splice(foundIndex, 1);
		    	}	

	    	}
		}
		else {
			if(!$scope.checkChild[inventory.id]) {
				var foundIndex 					= $scope.SelectedItems.indexOf(inventory);
	    		$scope.checkChild[inventory.id] = false;
				$scope.SelectedItems.splice(foundIndex, 1);
			}
			else {
				$scope.SelectedItems.push(inventory);
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
		var codeType = $filter('filter')($scope.itemCodeTypes, { name: type }, true);
		var match 	 = $filter('filter')(data, { item_code_type_id: codeType[0].id }, true);
		return match.reverse()[0];
	}

	$scope.orderByName = function(data) {
      return data[0].item.name;
    };

	$scope.transfer = function(status) {
		if(status!=null)
		{
			var data = $scope.SelectedItems[0];
			var active = $filter('filter')($scope.itemStatus,{ id: status }, true)[0];
			if($scope.countSelectedItems==1) {
				$scope.modal = {
					action	: 'change-quantity',
	            	title	: "Transfer to '"+ active.name +"' Item - " + data.item.name + " (" + data.quantity + ")",
	            	field	: { quantity: 'Quantity (only)', remarks: 'Remarks (change)' },
	            	data	: {  
	            		status 		: status, 
	            		type 		: 'transfer_or_create',
	            		index 		: $scope.inventories.indexOf(data), 
	            		quantity 	: data.quantity, 
	            		remarks 	: data.remarks, 
	            		inventory 	: data
	            	},
	            	button	: 'Transfer'
	            };

	            $('#inventoryModal').modal('show');
			}
			else {
				if (confirm($scope.countSelectedItems + " item/s will be moved to status '" + active.name + "'. Continue?")) {
			        $http({
			            method 	: 'POST',
			            url 	: API_URL + 'inventories/transfer/' + status,
			            data 	: $scope.SelectedItems
			        })
		            .then(function (response) {
						console.log(response.data);
		            });

			        angular.forEach($scope.SelectedItems, function(item){ 
			        	var index = $scope.inventories.indexOf(item);
						$scope.inventories.splice(index,1);
					});
					$scope.countSelectedItems 	= 0;
			        $scope.SelectedItems 		= [];
			    }
			    else {

			    }
			}
		}
		$scope.selectedStatus = null;	
			
	}

	$scope.toggle = function(type, data) {
        var index = $scope.inventories.indexOf(data);

        switch (type) {
            case 'item':
                $scope.modal = {
                	title: "Modify Item",
                	field: { name: 'Name', description: 'Description' },
                	data: { 
                		type 		: type, 
                		index 		: index,
                		id 			: data.item.id, 
                		name 		: data.item.name, 
                		description : data.item.description
                	},
                	button: 'Save changes'
                };	
            	break;
            case 'item_code':
            	var itemCode = $scope.code(data.item.item_codes, 'Barcode');
                $scope.modal = {
                	title: "Modify Item Code",
                	field: { code: 'Barcode' },
                	data: { 
                		type 	: type, 
                		index 	: index,
                		code 	: itemCode.code, 
                		id 		: itemCode.id 
                	},
                	button: 'Save changes'
                };	
            	break;

            case 'item_price':
            	var itemPrice = data.item_prices[data.item_prices.length - 1];
                $scope.modal = {
                	title: "Modify Market Price",
                	field: { market_price: 'Market Price' },
                	data: { 
                		type 			: type, 
                		index 			: index,
                		market_price 	: itemPrice.market_price, 
                		market_price_id :itemPrice.id, 
                		id 				: data.id 
                	},
                	button: 'Save changes'
                };	
            	break;

            default:
                break;
        }
        $('#inventoryModal').modal('show');
    }

    $scope.update = function(data) {
        
        switch (data.type) {
            case 'item':
                $http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/update',
		            data 	: data
		        })
	            .then(function (response) {
	            	var findMatchItem = $filter('filter')($scope.inventories, { item_id: $scope.inventories[data.index].item_id }, true);
	            	angular.forEach(findMatchItem, function (inventory) {
	            		var key = $scope.inventories.indexOf(inventory);
	            		$scope.inventories[key].item = response.data;
	            	});
	            	$('#inventoryModal').modal('hide');
	            });
                break;

            case 'item_code':
                $http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/update',
		            data 	: data
		        })
	            .then(function (response) {
					var itemCodes 				= $scope.inventories[data.index].item.item_codes;
					$scope.code(itemCodes).code = response.data.code;
					$('#inventoryModal').modal('hide');
	            });
                break; 

            case 'item_price':
                $http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/update',
		            data 	: data
		        })
	            .then(function (response) {
	            	var inventory 	= $filter('filter')($scope.inventories, { id:response.data.id }, true)[0];
	            	var count 		= inventory.item_prices.length;
	            	var index 		= $scope.inventories.indexOf(inventory);

	            	$scope.inventories[index].item_prices[count-1].market_price = data.market_price;
					$('#inventoryModal').modal('hide');
	            });
                break;           

            case 'transfer_or_create':
            	$http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/transferOrCreate',
		            data 	: data
		        })
	            .then(function (response) {
					var index = $scope.inventories.indexOf($scope.SelectedItems[0]);
					if(response.data.quantity > 0) {
						$scope.inventories[index].quantity 	= parseInt(response.data.quantity);
						$scope.checkParent 					= [];
						$scope.checkChild 					= [];
					}
					else {
						$scope.inventories.splice(index,1);
					}
					
					$scope.SelectedItems 		= [];
					$scope.countSelectedItems 	= 0;

					$('#inventoryModal').modal('hide');
						
					
	            });
            	break;  

            default:
                break;
        }

    }
});


