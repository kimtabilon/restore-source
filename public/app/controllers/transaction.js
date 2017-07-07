app
.controller('transactionsController', function($scope, $http, $location, $filter, API_URL) { 
	$http
    .get(API_URL + 'transactions')
    .then(function (response) {
        $scope.transactions = response.data;
    });

    $http
    .get(API_URL + 'transactions/data')
    .then(function (response) {
        $scope.donors           = response.data.donors;
        $scope.items            = response.data.items;
        $scope.payment_types    = response.data.payment_types;
        $scope.item_status      = response.data.item_status;
        $scope.inventories      = response.data.inventories;
        $scope.itemCodeTypes    = response.data.code_types;
        $scope.categories       = response.data.categories;

        $scope.new_item     = $scope.items[0];
        $scope.new_item     = {
            category    : {
                name        : '',
                description : '',
            },
            name        : '',
            description : '',
            code        : ''
        }
        $scope.new_customer = $scope.donors[0];
        $scope.new_customer = { 
                given_name: '',
                middle_name: '',
                last_name: '',
                email: '',
                profile : {
                    title: '',
                    address: '',
                    phone: '',
                    tel: '',
                    company: '',
                    job_title: '',
                }                
            }
    });

    $scope.code = function(codes, type) {
        var codeType    = $filter('filter')($scope.itemCodeTypes, { name: type });
        var match       = $filter('filter')(codes, { item_code_type_id: codeType[0].id });
        return match.reverse()[0];
    }

    $scope.toggle = function(transaction) {
        $scope.modal = {
            title       : 'Items for transaction# ' + transaction.dt_number,
            inventories : transaction.inventories,
        }
        $('#inventoryModal').modal('show');
    }

    $scope.new_transaction = function () {
        $scope.transaction_no = 'DT-' + Math.random().toString(36).split('').filter( function(value, index, self) { 
                        return self.indexOf(value) === index;
                    }).join('').substr(2,8);

        $scope.acknowledgement_no = 'DA-' + Math.random().toString(36).split('').filter( function(value, index, self) { 
                        return self.indexOf(value) === index;
                    }).join('').substr(2,8);

        // $scope.acknowledgement_no   = 'DA-' + rand;
        $scope.good_status      = $filter('filter')($scope.item_status, { name: 'Good'}, true);
        $scope.selected_status  = $scope.selected_status || $scope.good_status[0].id;

        $scope.modal = {
            title : 'New',
        }
        $('#transactionModal').modal('show');
    }

    $scope.inventory_status_change = function() {
        $http
        .get(API_URL + 'transactions/inventories/' + $scope.selected_status)
        .then(function (response) {
            console.log(response.data);
            $scope.inventories = response.data;
        });
    }
    $scope.payment_type = [];
    $scope.choose_payment_type = function(id) {
        $scope.payment_type = $filter('filter')($scope.payment_types, {id:id}, true)[0];
        // console.log(selected_payment_type);
    }
    $scope.donor = [];
    $scope.choose_donor = function(id) {
        $scope.donor = $filter('filter')($scope.donors, {id:id}, true)[0];
        // console.log(selected_donor);
    }

    var selected     = [];
    $scope.added_items  = [];

    $scope.new_inv_status_selected = function(id) {
        $scope.active_status = $filter('filter')($scope.item_status, { id: id }, true)[0];
    }

    $scope.choose_item_from_item = function(id) {
        var item = $filter('filter')($scope.items, { id: id})[0];
        selected = angular.copy($scope.inventories[0]);
        selected.id      = 0;
        selected.item    = item;
        selected.item_id = item.id;
    }

    $scope.choose_item_from_inv = function(id) {
        var selected_inventory = $filter('filter')($scope.inventories, { id: id}, true)[0];
        selected = selected_inventory;
    }

    $scope.add_item_to_transaction = function() {
        if(selected.id==0) {
            selected.item_status     = $scope.active_status;
            selected.item_status_id  = $scope.active_status.id;
            selected.quantity        = $scope.new_inv_qty;
            selected.remarks         = $scope.new_inv_remarks;
        }

        var copy_selected = angular.copy(selected);

        $scope.added_items
            .push(
                copy_selected 
            ); 
    }

    $scope.cashier_add_item = function(inventory) {
        $scope.added_items
            .push(
                inventory 
            );
    }

    $scope.remove_item_from_transaction = function(index) {
        $scope.added_items.splice(index, 1);
    }

    $scope.save_transaction = function() {
        var continue_transaction = true;

        if($scope.acknowledgement_no == '') {
            alert('Please reload page and open again. Auto generate of Acknowledgement Number not working. Or contact your admin.');
            continue_transaction = false;
        }

        if($scope.transaction_no == '') {
            alert('Please reload page and open again. Auto generate of Transaction Number not working. Or contact your admin.');
            continue_transaction = false;
        }

        if($scope.donor.length == 0) {
            alert('Please select donor for this transaction');
            continue_transaction = false;
        }

        if($scope.payment_type.length == 0) {
            alert('Please select type for this transaction');
            continue_transaction = false;
        }

        if($scope.added_items.length == 0) {
            alert('No item added in this transaction!');
            continue_transaction = false;
        }

        if(continue_transaction) {
            $http({
                method  : 'POST',
                url     : API_URL + 'transactions/create',
                data    : {  
                            donor  : $scope.donor,
                            payment: $scope.payment_type,
                            items  : $scope.added_items,
                            da_no  : $scope.acknowledgement_no,
                            dt_no  : $scope.transaction_no
                        }
            })
            .then(function (response) {
                console.log(response.data);
                $scope.transactions.push(response.data);
                $scope.added_items = [];
            });

            $('#transactionModal').modal('hide');
        }
    }

    $scope.new_item_btn = function(new_item) {
        var match_category      = $filter('filter')($scope.categories, { name: new_item.category.name }, true);
        var category_index      = $scope.categories.indexOf(match_category[0]);

        new_item.category_id    = match_category.length==0 ? 0 : match_category[0].id;
        new_item.type           = 'new_item'; 
        new_item.code_type      = $filter('filter')($scope.itemCodeTypes, { name: 'Barcode' })[0].id; 
        
        $http({
            method  : 'POST',
            url     : API_URL + 'inventories/update',
            data    : new_item
        })
        .then(function (response) {
            var cat_count   = $scope.categories.length;
            var item        = response.data.item;
            var code        = response.data.code;
            var new_cat     = response.data.new_category;
            var category    = response.data.category;

            if(new_cat) {
                $scope.categories.push({
                    id          : category.id,
                    name        : category.name,
                    description : category.description,
                    items       : [{ 
                                    id          : item.id,
                                    category_id : item.category_id,
                                    name        : item.name,
                                    description : item.description,
                                    item_codes  : [ code ]
                                }]
                });
            }
            else {
                $scope.items.push({
                    id          : item.id,
                    category_id : item.category_id,
                    name        : item.name,
                    description : item.description,
                    item_codes  : [ code ]
                });
            }
            
        });
    }

    $scope.remove_item = function(id, index) {
        if(confirm('Data will be lost, please make sure this ITEM is new and no transaction created.'))
        {
            $http({
                method  : 'POST',
                url     : API_URL + 'items/destroy',
                data    : {  
                            id  : id,
                        }
            })
            .then(function (response) {
                $scope.items.splice(index, 1);
            });
        }
        
    }

    $scope.new_customer_btn = function(new_customer) {
        $http({
            method  : 'POST',
            url     : API_URL + 'donors/create',
            data    : {  
                        donor  : new_customer,
                    }
        })
        .then(function (response) {
            console.log(response.data);
            $scope.donors.push(
                response.data
            );
        });
    }

    $scope.remove_donor = function(id, index) {
        if(confirm('Data will be lost, please make sure this donor is new and no transaction created.'))
        {
            $http({
                method  : 'POST',
                url     : API_URL + 'donors/destroy',
                data    : {  
                            id  : id,
                        }
            })
            .then(function (response) {
                // console.log(response.data);
                $scope.donors.splice(index, 1);
            });
        }
        
    }


                
});


