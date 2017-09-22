app.controller('discountsController', function($scope, $http, $location, $filter, API_URL) { 
	
    $http
    .get(API_URL + 'item-discounts')
    .then(function (response) {
    	$scope.discounts = response.data;
    });


    $scope.toggle = function(discount, action) {
        switch (action) {
            case 'new':              
                $scope.modal = {
                    title: "Create Discount",
                    field: { 
                        percent     : 'Percent', 
                        type        : 'Name', 
                        remarks     : 'Remarks', 
                        start_date  : 'Start Date', 
                        end_date    : 'End Date', 
                    },
                    data: { 
                        action      : action,
                        percent     : 20, 
                        type        : 'Holiday Sale', 
                        remarks     : '', 
                        start_date  : '', 
                        end_date    : '', 
                    },
                    button: 'Create'
                };  
                break;
            case 'edit':
                var found_discount  = $filter('filter')($scope.discounts, {'id':discount.id}, true)[0]
                var discount_index  = $scope.discounts.indexOf(found_discount);

                $scope.modal = {
                    title: "Modify Discount",
                    field: { 
                        percent     : 'Percent', 
                        type        : 'Name', 
                        remarks     : 'Remarks', 
                        start_date  : 'Start Date', 
                        end_date    : 'End Date', 
                    },
                    data: { 
                        action      : action,
                        inventories : discount.inventories, 
                        percent     : discount.percent, 
                        type        : discount.type, 
                        remarks     : discount.remarks, 
                        start_date  : discount.start_date, 
                        end_date    : discount.end_date, 
                        id          : discount.id, 
                    },
                    button: 'Save changes'
                };  
                break;

            default:
                break;
        }
        $('#itemDiscountModal').modal('show');
    }

    $scope.update = function(discount) {
        
        switch (discount.action) {
            case 'new':
                $http({
                    method  : 'POST',
                    url     : API_URL + 'item-discounts/save',
                    data    : discount
                })
                .then(function (response) {
                    $scope.discounts.push(response.data);
                    $('#itemDiscountModal').modal('hide');
                });
                break;

            case 'edit':
                $http({
                    method  : 'POST',
                    url     : API_URL + 'item-discounts/save',
                    data    : discount
                })
                .then(function (response) {
                    var discount = $filter('filter')($scope.discounts, { id: response.data.id })[0];
                    var index = $scope.discounts.indexOf(discount);
                    $scope.discounts[index] = response.data;
                    $('#itemDiscountModal').modal('hide');
                });
                break;    

            default:
                break;
        }

    }

    $scope.remove = function(discount) {
        if(confirm(discount.inventories.length+' inventories affected. Confirm delete discount?'))
        {
            $http({
                method  : 'POST',
                url     : API_URL + 'item-discounts/destroy',
                data    : discount
            })
            .then(function (response) {
                var index = $scope.discounts.indexOf(discount);
                $scope.discounts.splice(index, 1);
            });
        }
        
    }

});


