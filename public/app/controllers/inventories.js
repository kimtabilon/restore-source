app.controller('inventoriesController', function($scope, $http, $location, $filter, API_URL) {	

	$http
	.get(API_URL + 'item-status-and-code-types')
	.then(function(response) {
		$scope.itemStatus 		= response.data.status;
		$scope.itemCodeTypes 	= response.data.code_types;
		$scope.itemImages 		= response.data.item_images;
		$scope.itemDiscounts	= response.data.item_discounts;
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
	            		unit 		: data.unit, 
	            		inventory 	: data
	            	},
	            	button	: 'OK'
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
						// console.log(response.data);
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
            	var itemCode = $scope.code(data.item_codes, 'Barcode');
                $scope.modal = {
                	title: "Modify Item Code",
                	field: { code: 'Barcode' },
                	data: { 
                		inventory: data,
                		type 	 : type, 
                		index 	 : index,
                		code 	 : itemCode.code, 
                		id 		 : itemCode.id 
                	},
                	button: 'Generate New Code'
                };	
            	break;

            case 'item_price':
            	var itemPrice = data.item_prices[data.item_prices.length - 1];
                $scope.modal = {
                	title: "Modify Market Value",
                	field: { market_price: 'Market Value' },
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

            case 'item_selling_price':
            	var itemSellingPrice = data.item_selling_prices[data.item_selling_prices.length - 1];
                $scope.modal = {
                	title: "Modify New Value",
                	field: { market_price: 'New Value' },
                	data: { 
                		type 			: type, 
                		index 			: index,
                		market_price 	: itemSellingPrice.market_price, 
                		market_price_id : itemSellingPrice.id, 
                		id 				: data.id 
                	},
                	button: 'Save changes'
                };	
            	break;	

            case 'item_restore_price':
            	var itemRestorePrice = data.item_restore_prices[data.item_restore_prices.length - 1];
                $scope.modal = {
                	title: "Modify ReStore Value",
                	field: { market_price: 'ReStore Value' },
                	data: { 
                		type 			: type, 
                		index 			: index,
                		market_price 	: itemRestorePrice.market_price, 
                		market_price_id : itemRestorePrice.id, 
                		id 				: data.id 
                	},
                	button: 'Save changes'
                };	
            	break;		

            case 'remarks':
                $scope.modal = {
                	title: "Modify Remarks",
                	field: { remarks: 'Edit Remarks' },
                	data: { 
                		type 			: type, 
                		index 			: index,
                		remarks 		: data.remarks, 
                		id 				: data.id 
                	},
                	button: 'Save changes'
                };	
            	break;	

            case 'unit':
                $scope.modal = {
                	title: "Modify Unit",
                	field: { unit: 'Edit Unit' },
                	data: { 
                		type 			: type, 
                		index 			: index,
                		unit 			: data.unit, 
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
            	data.code = $scope.generate_code('RS');
            	console.log(data);
                $http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/update',
		            data 	: data
		        })
	            .then(function (response) {
					var itemCodes 				= $scope.inventories[data.index].item_codes;
					$scope.code(itemCodes).code = response.data.code;
					$scope.code(itemCodes).barcode = response.data.barcode;
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

            case 'item_selling_price':
                $http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/update',
		            data 	: data
		        })
	            .then(function (response) {
	            	var inventory 	= $filter('filter')($scope.inventories, { id:response.data.id }, true)[0];
	            	var count 		= inventory.item_selling_prices.length;
	            	var index 		= $scope.inventories.indexOf(inventory);

	            	$scope.inventories[index].item_selling_prices[count-1].market_price = data.market_price;
					$('#inventoryModal').modal('hide');
	            });
                break;  

            case 'item_restore_price':
                $http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/update',
		            data 	: data
		        })
	            .then(function (response) {
	            	var inventory 	= $filter('filter')($scope.inventories, { id:response.data.id }, true)[0];
	            	var count 		= inventory.item_restore_prices.length;
	            	var index 		= $scope.inventories.indexOf(inventory);

	            	$scope.inventories[index].item_restore_prices[count-1].market_price = data.market_price;
					$('#inventoryModal').modal('hide');
	            });
                break;             

            case 'remarks':
                $http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/update',
		            data 	: data
		        })
	            .then(function (response) {
	            	var inventory 	= $filter('filter')($scope.inventories, { id:response.data.id }, true)[0];
	            	var index 		= $scope.inventories.indexOf(inventory);

	            	$scope.inventories[index].remarks = response.data.remarks;
					$('#inventoryModal').modal('hide');
	            });
                break;

            case 'unit':
                $http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/update',
		            data 	: data
		        })
	            .then(function (response) {
	            	var inventory 	= $filter('filter')($scope.inventories, { id:response.data.id }, true)[0];
	            	var index 		= $scope.inventories.indexOf(inventory);
	            	
	            	$scope.inventories[index].unit = response.data.unit;
					$('#inventoryModal').modal('hide');
	            });
                break;                     

            case 'transfer_or_create':
            	console.log(data);
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

    $scope.display_image = function(inventory, type){
    	var images = type=='ref' ? inventory.item_ref_images : inventory.item_images;

    	if(images.length>0) {
    		image = images[images.length-1];
	        $scope.modal = {
	        	type 		: type,
	        	inventory 	: inventory,
	            image       : image.id+'.'+image.type,
	            name        : inventory.item.name,
	            remarks 	: inventory.remarks
	        }
    	}
    	else {
    		$scope.modal = {
    			type 		: type,
	        	inventory 	: inventory,
	            image       : '',
	            name        : inventory.item.name,
	            remarks 	: inventory.remarks
	        }
    	}

        $('#imageModal').modal('show');
    }

    $scope.set_image = function(image, inventory, type) {
    	$http({
            method 	: 'POST',
            url 	: API_URL + 'inventories/add-image',
            data 	: { image: image.id, inventory: inventory.id, type: type }
        })
        .then(function (response) {
        	$scope.modal.image = image.id+'.'+image.type;
        	var index = $scope.inventories.indexOf(inventory);
        	var images = type=="ref" ? $scope.inventories[index].item_ref_images : $scope.inventories[index].item_images;

        	images[images.length] = image;
        });
    }

    $scope.show_discounts = function(inventory) {
    	$scope.modal = {
    		inventory : inventory,
        	discounts : inventory.item_discounts,
        }

        $('#discountModal').modal('show');
    }

    $scope.add_discount = function(discount, inventory) {
		$http({
            method 	: 'POST',
            url 	: API_URL + 'item-discounts/add',
            data 	: { discount: discount, inventory: inventory }
        })
        .then(function (response) {
        	var index = $scope.inventories.indexOf(inventory);
        	$scope.inventories[index].item_discounts.push(discount);
        });
    }

    $scope.remove_discount = function(discount, inventory) {
		$http({
            method 	: 'POST',
            url 	: API_URL + 'item-discounts/remove',
            data 	: { discount: discount, inventory: inventory }
        })
        .then(function (response) {
        	var i_index = $scope.inventories.indexOf(inventory);
        	var d_index 	= $scope.inventories[i_index].item_discounts.indexOf(discount);
        	$scope.inventories[i_index].item_discounts.splice(d_index, 1);
        });
    }

    $scope.sum = function(data, field) {
		var total = 0;
		angular.forEach(data, function(data) {
			total += parseInt(data[field]);
		});

		return parseInt(total);
	}

    $scope.new_value = function(inventory) {
    	var discount = $scope.sum(inventory.item_discounts, 'percent');
    	var prices   = inventory.item_selling_prices;
    	var price    = parseFloat(prices[prices.length-1].market_price);
    	return  price - (price*discount/100);
    }

    $scope.printable_barcode = function() {
    	$scope.modal = {
    		inventories : angular.copy($scope.inventories),
        }

        $('#barcodeModal').modal('show');
    }

    $scope.hide_parent = function(inventory) {
    	var index = $scope.modal.inventories.indexOf(inventory);

    	$scope.modal.inventories.splice(index, 1);
    }

    $scope.clone_parent = function(inventory) {
    	var i = angular.copy(inventory);
    	$scope.modal.inventories.push(i);
    }

    $scope.generate_code = function(begin_with) {
        auto_gen_code = begin_with + '-' + Math.random().toString(36).split('').filter( function(value, index, self) { 
                        return self.indexOf(value) === index;
                    }).join('').substr(2,8);
       
        return auto_gen_code;
    }
});


