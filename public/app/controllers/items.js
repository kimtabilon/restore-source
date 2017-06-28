app
.controller('itemsController', function($scope, $http, $location, $filter, API_URL) { 
	$http
	.get(API_URL + 'item-status-and-code-types')
	.then(function(response) {
		$scope.itemCodeTypes 	= response.data.code_types;
	});

    $http
    .get(API_URL + 'items')
    .then(function (response) {
    	$scope.categories = response.data;
    });

    $scope.code = function(codes, type) {
		var codeType 	= $filter('filter')($scope.itemCodeTypes, { name: type });
		var match 		= $filter('filter')(codes, { item_code_type_id: codeType[0].id });
		return match.reverse()[0];
	}

	$scope.toggle = function(type, item) {
		var found_category 	= $filter('filter')($scope.categories, {'id':item.category_id}, true)[0]
        var category_index 	= $scope.categories.indexOf(found_category);
        var item_index 		= $scope.categories[category_index].items.indexOf(item);

        switch (type) {
            case 'item':     
                console.log(item);           
                $scope.modal = {
                	title: "Modify Item",
                	field: { name: 'Name', description: 'Description' },
                	data: { 
                		type 		: type, 
                		category 	: category_index,
                		index 		: item_index,
                		id 			: item.id, 
                		name 		: item.name, 
                		description : item.description
                	},
                	button: 'Save changes'
                };	
            	break;
            case 'item_code':
            	var item_code = $scope.code(item.item_codes, 'Barcode');
                $scope.modal = {
                	title: "Modify Item Code",
                	field: { code: 'Barcode' },
                	data: { 
                		type 		: type, 
                		category 	: category_index,
                		index 		: item_index,
                		code 		: item_code.code, 
                		id 			: item_code.id 
                	},
                	button: 'Save changes'
                };	
            	break;
			
			case 'modify_category':
            	$scope.modal = {
                	title: "Modify Category",
                	field: { name: 'Name', description: 'Description' },
                	data: { 
                		type 		: type, 
                		category 	: category_index,
                		index 		: item_index,
                		id 			: found_category.id, 
                		name 		: found_category.name, 
                		description : found_category.description
                	},
                	button: 'Save changes'
                };	
            	break;

            	case 'new_item':
            	$scope.modal = {
                	title: "Create New Item",
                	field: { category: 'Category', name: 'Name', description: 'Description', code: 'Barcode' },
                	array : { category: $scope.categories, },
                	data: { 
                		type 		: type,  
                		category 	: '',
                		category_id : 0,
                		name 		: '', 
                		description : '',
                		code 		: '',
                		code_type 	: $scope.code(item.item_codes, 'Barcode').item_code_type_id,
                	},
                	button: 'Create'
                };	
            	break;

            default:
                break;
        }
        $('#inventoryModal').modal('show');
    }

    $scope.update = function(item) {
        
        switch (item.type) {
            case 'item':
                $http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/update',
		            data 	: item
		        })
	            .then(function (response) {
            		$scope.categories[item.category].items[item.index] = response.data;
	            	
	            	$('#inventoryModal').modal('hide');
	            });
                break;

            case 'item_code':
                $http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/update',
		            data 	: item
		        })
	            .then(function (response) {
					var item_codes 					= $scope.categories[item.category].items[item.index].item_codes;
					$scope.code(item_codes).code 	= response.data.code;
					$('#inventoryModal').modal('hide');
	            });
                break; 

            case 'modify_category':
            	$http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/update',
		            data 	: item
		        })
	            .then(function (response) {
            		$scope.categories[item.category].name 			= response.data.name;
            		$scope.categories[item.category].description 	= response.data.description;
	            	
	            	$('#inventoryModal').modal('hide');
	            });
            	break;    
			
			case 'new_item':
				var category 		= $filter('filter')($scope.categories, { name: item.category }, true);
				var category_index 	= $scope.categories.indexOf(category[0]);
				item.category_id 	= category.length==0 ? 0 : category[0].id; 
            	
            	$http({
		            method 	: 'POST',
		            url 	: API_URL + 'inventories/update',
		            data 	: item
		        })
	            .then(function (response) {
	            	var cat_count 	= $scope.categories.length;
	            	var item 		= response.data.item;
	            	var code 		= response.data.code;
	            	var new_cat 	= response.data.new_category;
	            	var category 	= response.data.category;

	            	if(new_cat) {
	            		$scope.categories.push({
	            			id 			: category.id,
	            			name 		: category.name,
	            			description : category.description,
	            			items 		: [{ 
                                            id          : item.id,
	            							category_id	: item.category_id,
	            							name 		: item.name,
	            							description : item.description,
	            							item_codes 	: [ code ]
            							}]
	            		});
	            	}
	            	else {
	            		var item_count 	= $scope.categories[category_index].items.length;

	            		$scope.categories[category_index].items.push({
                            id          : item.id,
	            			category_id : item.category_id,
	            			name 		: item.name,
	            			description : item.description,
	            			item_codes 	: [ code ]
	            		});
	            	}
	            	
	            	$('#inventoryModal').modal('hide');
	            });
            	break;    

            default:
                break;
        }

    }
});


