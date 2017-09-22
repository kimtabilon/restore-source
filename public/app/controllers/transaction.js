app.controller('transactionsController', function($scope, $http, $location, $filter, API_URL) { 
	$http
    .get(API_URL + 'transactions')
    .then(function (response) {
        $scope.types = response.data;
    });

    $http
    .get(API_URL + 'transactions/data')
    .then(function (response) {
        $scope.donors           = response.data.donors;
        $scope.donor_types      = response.data.donor_types;
        $scope.items            = response.data.items;
        $scope.item_status      = response.data.item_status;
        $scope.inventories      = response.data.inventories;
        $scope.itemCodeTypes    = response.data.code_types;
        $scope.categories       = response.data.categories;

        $scope.special_discount = 0;
        $scope.remarks          = '';

        $scope.new_item     = $scope.items[0];
        $scope.new_item     = {
            category    : {
                name        : '',
                description : '',
            },
            name        : '',
            description : '',
        }
        $scope.new_customer = $scope.donors[0];
        $scope.new_customer = { 
                id          : 0,
                donor_type  : '',
                given_name  : '',
                middle_name : '',
                last_name   : '',
                email       : '',
                profile     : {
                    title   : '',
                    address : '',
                    phone   : '',
                    tel     : '',
                    company : '',
                    job_title: '',
                },
                store_credits : [{ amount: 0 }]                
        }

        // console.log($scope.inventories);
    });

    $scope.code = function(codes, type) {
        var codeType    = $filter('filter')($scope.itemCodeTypes, { name: type });
        var match       = $filter('filter')(codes, { item_code_type_id: codeType[0].id });
        return match.reverse()[0];
    }

    $scope.toggle = function(transaction) {
        var donors = transaction.inventories[0].donors;
        var d = donors[donors.length - 1];
        $scope.modal = {
            title       : transaction.da_number + ' - ' + d.name + ' / ' + (d.profile.company || ''),
            trans       : transaction,
            inventories : transaction.inventories,
        }
        $('#inventoryModal').modal('show');
    }

    $scope.new_transaction = function () {
        $scope.acknowledgement_no = $scope.generate_code('DA');

        $scope.good_status      = $filter('filter')($scope.item_status, { name: 'Good'}, true);
        $scope.selected_status  = $scope.selected_status || $scope.good_status[0].id;

        $scope.modal = {
            title : 'New',
        }
        $('#transactionModal').modal('show');
    }

    $scope.generate_code = function(begin_with) {
        auto_gen_code = begin_with + '-' + Math.random().toString(36).split('').filter( function(value, index, self) { 
                        return self.indexOf(value) === index;
                    }).join('').substr(2,8);
        /*$http
        .get(API_URL + 'transactions/check_code/' + begin_with + '/' + auto_gen_code)
        .then(function (response) {
            // $scope.inventories = response.data;
            if(response.data.length == 0) {
                $scope.auto_gen_code = auto_gen_code;
            }
            else {
                $scope.auto_gen_code = $scope.generate_code(begin_with);
            }
            console.log($scope.auto_gen_code);
        });*/
        return auto_gen_code;
    }

    $scope.inventory_status_change = function(selected_status) {
        $http
        .get(API_URL + 'transactions/inventories/' + selected_status)
        .then(function (response) {
            $scope.inventories = response.data;
        });
    }

    $scope.payment_type = [];
    $scope.new_inv = {
        code            : '',
        market_price    : 0,
        selling_price   : 0,
        restore_price   : 0,
        remarks         : '',
    };

    $scope.choose_payment_type = function(id) {
        $scope.payment_type = $filter('filter')($scope.types, {id:id}, true)[0];
        $scope.remarks = $scope.payment_type.name;
        var paytype = $scope.payment_type.name;
        if(paytype == 'Cash'||paytype == 'Debit'||paytype == 'Credit') {
            $scope.acknowledgement_no = $scope.generate_code('C');
        }
        else {
            $scope.acknowledgement_no = $scope.generate_code('DA');
            $scope.new_inv ={ 
                code            : $scope.generate_code('RS'),
                market_price    : 0,
                selling_price   : 0,
                restore_price   : 0,
                remarks         : '',
            };
        }
        // console.log($scope.new_inv.code);
    }
    
    $scope.donor = [];
    
    $scope.choose_donor = function(id) {
        $scope.donor = $filter('filter')($scope.donors, {id:id}, true)[0];
    }

    var selected        = [];
    $scope.added_items  = [];

    $scope.new_inv_status_selected = function(id) {
        $scope.active_status = $filter('filter')($scope.item_status, { id: id }, true)[0];
    }

    $scope.choose_item_from_item = function(id) {
        var item = $filter('filter')($scope.items, { id: id}, true)[0];
        selected = {
            id              : 0,
            item            : item,
            item_id         : item.id
        };
    }

    $scope.choose_item_from_inv = function(id) {
        var selected_inventory = $filter('filter')($scope.inventories, { id: id}, true)[0];
        selected = selected_inventory;
    }

    $scope.add_item_to_transaction = function() {
        if(selected.id==0) {
            var codeType    = $filter('filter')($scope.itemCodeTypes, { name: 'Barcode' });
            console.log(selected.item_id);
            if(selected.item_id == 0)
            {
                selected = {
                    id              : 0,
                    item            : $scope.added_items[$scope.added_items.length-1].item,
                };
                // selected.item =  copy_selected.item;
            }
            
            selected = {
                item            : selected.item,
                item_id         : selected.item.id,
                item_status     : $scope.active_status,
                item_status_id  : $scope.active_status.id,
                quantity        : $scope.new_inv.quantity,
                unit            : $scope.new_inv.unit,
                item_codes      : [{ 
                            code                : $scope.new_inv.code,
                            item_code_type_id   : codeType[0].id, 
                        }],
                item_prices     : [{ market_price: $scope.new_inv.market_price }],
                item_selling_prices : [{ market_price: $scope.new_inv.selling_price }],
                item_restore_prices : [{ market_price: $scope.new_inv.restore_price }],
                remarks         : $scope.new_inv.remarks,
            };
            
        }
        // console.log(selected);

        var copy_selected = angular.copy(selected);

        $scope.added_items
            .push(
                copy_selected 
            );  
        // console.log(copy_selected);    

        /* SET EMPTY AGAIN */   

        selected = {
            id              : 0,
            item            : {},
            item_id         : 0
        };
        // $scope.selected_item_from_items = {};
         
        $scope.new_inv ={ 
            code            : $scope.generate_code('RS'),
            market_price    : 0,
            selling_price   : 0,
            restore_price   : 0,
            remarks         : '',
        };  
        console.log(selected);
    }

    $scope.cashier_add_item = function(inventory) {
        var copy_inventory = angular.copy(inventory);
        
        $scope.added_items
            .push(
                copy_inventory 
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
                            remarks: $scope.remarks,
                            special_discount: $scope.special_discount,
                        }
            })
            .then(function (response) {
                /*APPEND VALUES*/
                var new_trans = response.data;
                var match = $filter('filter')($scope.types, { id: new_trans.payment_type.id }, true)[0];
                var index = $scope.types.indexOf(match);
                $scope.types[index].transactions.push(new_trans);

                /*DEDUCT VALUES*/
                angular.forEach($scope.added_items, function(i){
                    var match = $filter('filter')($scope.inventories, {id:i.id}, true)[0];

                    match.quantity -= i.quantity;

                    console.log(match.quantity);
                });


                /*CLEAR VALUES*/
                $scope.added_items = [];
                $scope.selected_donor = [];
                $scope.selected_payment_type = [];
                $scope.special_discount = 0;
                $scope.remarks = '';
            });

            

            $('#transactionModal').modal('hide');
        }
    }

    $scope.new_item_btn = function(new_item) {
        var match_category      = $filter('filter')($scope.categories, { name: new_item.category_name }, true);
        var category_index      = $scope.categories.indexOf(match_category[0]);

        new_item.category_id    = match_category.length==0 ? 0 : match_category[0].id;
        new_item.category       = match_category.length==0 ? new_item.category_name : match_category[0].name;
        new_item.type           = 'new_item'; 
        // new_item.code_type      = $filter('filter')($scope.itemCodeTypes, { name: 'Barcode' }, true)[0].id; 
        
        $http({
            method  : 'POST',
            url     : API_URL + 'inventories/update',
            data    : new_item
        })
        .then(function (response) {
            var cat_count   = $scope.categories.length;
            var item        = response.data.item;
            // var code        = response.data.code;
            var new_cat     = response.data.new_category;
            var category    = response.data.category;
            
            if(new_cat) {
                $scope.categories.push({
                    id          : category.id,
                    name        : category.name,
                    description : category.description,
                });

                $scope.items.push({
                    id          : item.id,
                    category_id : item.category_id,
                    category    : category,
                    name        : item.name,
                    description : item.description,
                    // item_codes  : [ code ]
                });
            }
            else {
                // console.log(category);
                $scope.items.push({
                    id          : item.id,
                    category_id : item.category_id,
                    category    : match_category[0],
                    name        : item.name,
                    description : item.description,
                    // item_codes  : [ code ]
                });
            }

            $scope.new_item = {
                category    : {
                    name        : '',
                    description : '',
                },
                name        : '',
                description : '',
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

        if(new_customer.donor_type != "" || confirm('Create new customer?')) {
            new_customer.donor_type = new_customer.donor_type != '' ? new_customer.donor_type : 'Customer';

            $http({
                method  : 'POST',
                url     : API_URL + 'donors/create',
                data    : {  
                            donor  : new_customer,
                        }
            })
            .then(function (response) {
                // console.log(response.data);
                $scope.donors.push(
                    response.data
                );

                $scope.new_customer = {
                    id: 0, 
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
                    },
                    store_credits : [{ amount: 0 }]                
                }
            });
        }     
    }

    $scope.remove_donor = function(donor) {
        if(confirm('Data will be lost, please make sure this donor is new and no transaction created.'))
        {
            $http({
                method  : 'POST',
                url     : API_URL + 'donors/destroy',
                data    : {  
                            id  : donor.id,
                        }
            })
            .then(function (response) {
                var match = $filter('filter')($scope.donors, { id: donor.id }, true)[0];
                var index = $scope.donors.indexOf(match);
                $scope.donors.splice(index, 1);
            });
        }
        
    }

    $scope.sum = function(data, field) {
        var total = 0;
        angular.forEach(data, function(data) {
            total += parseInt(data[field]);
        });

        return parseFloat(total);
    }

    $scope.trans_total_each = function(ins) {
        var total = 0;
        angular.forEach(ins, function(i){
            total+= (parseFloat(i.item_restore_prices[ i.item_restore_prices.length - 1].market_price) * parseFloat(i.quantity));
        });

        return parseFloat(total);
    }

    $scope.new_value = function(inventory) {
        var discount = $scope.sum(inventory.item_discounts, 'percent');
        var prices   = inventory.item_selling_prices;
        var price    = parseFloat(prices[prices.length-1].market_price);
        return  price - (price*discount/100);
    }

    $scope.total_transaction = function (inventories) {
        var special_discount = $scope.special_discount;
        var total = 0;
        angular.forEach(inventories, function(i) {
            // total += $scope.new_value(i) * i.quantity;
            var restore_value = i.item_restore_prices[i.item_restore_prices.length-1].market_price;
            total += restore_value * i.quantity;
        });
        return total - special_discount;
    }
                
});


