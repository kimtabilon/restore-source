app.controller('itemsController', function($scope, $http, $location, $filter, API_URL) { 
	$http
	.get(API_URL + 'item-status-and-code-types')
	.then(function(response) {
		$scope.itemCodeTypes 	= response.data.code_types;
	});

    $http
    .get(API_URL + 'items')
    .then(function (response) {
        $scope.categories  = response.data.categories;
    });

    $scope.code = function(codes, type) {
		var codeType 	= $filter('filter')($scope.itemCodeTypes, { name: type });
		var match 		= $filter('filter')(codes, { item_code_type_id: codeType[0].id });
		return match.reverse()[0];
	}

	$scope.toggle = function(type, item) {

        switch (type) {
            case 'item':   
                var found_category  = $filter('filter')($scope.categories, {'id':item.category_id}, true)[0]
                var category_index  = $scope.categories.indexOf(found_category);
                var item_index      = $scope.categories[category_index].items.indexOf(item);
        
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
            /*case 'item_code':
                var found_category  = $filter('filter')($scope.categories, {'id':item.category_id}, true)[0]
                var category_index  = $scope.categories.indexOf(found_category);
                var item_index      = $scope.categories[category_index].items.indexOf(item);

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
            	break;*/
			
			case 'modify_category':
                var found_category  = $filter('filter')($scope.categories, {'id':item.category_id}, true)[0]
                var category_index  = $scope.categories.indexOf(found_category);
                var item_index      = $scope.categories[category_index].items.indexOf(item);

            	$scope.modal = {
                	title: "Modify Department",
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
                	field: { category: 'Department', name: 'Name', description: 'Description' },
                	array : { category: $scope.categories, },
                	data: { 
                		type 		: type,  
                		category 	: '',
                		category_id : 0,
                		name 		: '', 
                		description : ''
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

            /*case 'item_code':
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
                break; */

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
                    // console.log(response.data);
	            	var cat_count 	= $scope.categories.length;
	            	var item 		= response.data.item;
	            	// var code 		= response.data.code;
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
	            							// item_codes 	: [ code ]
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
	            			// item_codes 	: [ code ]
	            		});
	            	}
	            	
	            	$('#inventoryModal').modal('hide');
	            });
            	break;    

            default:
                break;
        }

    }

    $scope.delete_category = function(category) {
        if(confirm('Items belong in this category will be lost. Continue?'))
        {
            $http({
                method  : 'POST',
                url     : API_URL + 'items/category/destroy',
                data    : category
            })
            .then(function (response) {
                var index = $scope.categories.indexOf(category);
                $scope.categories.splice(index, 1);
                
                $('#inventoryModal').modal('hide');
            });
        }
    }

    $scope.delete_item = function(item, category) {
        console.log(item);
        if(confirm('Data will be lost, please make sure this ITEM is new and no transaction created.'))
        {
            $http({
                method  : 'POST',
                url     : API_URL + 'items/destroy',
                data    : {  
                            id  : item.id,
                        }
            })
            .then(function (response) {
                var cat_index = $scope.categories.indexOf(category);
                var item_index = $scope.categories[cat_index].items.indexOf(item);
                $scope.categories[cat_index].items.splice(item_index, 1);
            });
        }
    }
});


