app.controller('dashboardController', function ($scope, $http, $location, $filter, API_URL) {

    var current         = $filter('date')(Date.now(), 'yyyy-MM-dd');
    var date            = new Date();
    var current_date    = date.getDate();
    var current_month   = date.getMonth() + 1;
    var current_year    = date.getFullYear();

    $scope.report = {
        from : current_year + '-01-01',
        to   : current,
    };

    $scope.itemStatusColors = [
        "#f56954",
        "#00a65a",
        "#f39c12",
        "#00c0ef",
        "#3c8dbc",
        "#d2d6de",
        "#4661EE",
        "#EC5657",
        "#1BCDD1",
        "#8FAABB",
        "#B08BEB",
        "#F5A52A",
        "#3EA0DD",
        "#23BFAA",
        "#FAA586",
        "#EB8CC6",
    ];

    /*var date1       = new Date($scope.report.from);
    var date2       = new Date($scope.report.to);
    var timeDiff    = Math.abs(date2.getTime() - date1.getTime());
    var diffDays    = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
    console.log(diffDays);*/

    /*
    less than or equal 7 days, daily
    greater than 7 days, weekly
    greater than 31 days, monthly
    greater than 365 days, yearly
    */

	$http
    .get(API_URL + 'dashboard/' + $scope.report.from + '/' + $scope.report.to)
    .then(function (response) {
        $scope.users        = response.data.users;
        $scope.items        = response.data.items;
        $scope.inventories  = response.data.inventories;
        $scope.donors       = response.data.donors;
        $scope.itemStatus   = response.data.itemStatus;
    	$scope.transactions = response.data.transactions;

        $scope.good_items = $filter('filter')($scope.itemStatus, { name: 'Good'}, true)[0];
        $scope.sold_items = $filter('filter')($scope.itemStatus, { name: 'Sold'}, true)[0];
    });

    $scope.show_report = function() {
        var from = new Date($scope.report.from);
        var to   = new Date($scope.report.to);
        console.log(from);
        from = from.getFullYear() + '-' + (from.getMonth() + 1) + '-' + from.getDate();
        to   = to.getFullYear() + '-' + (to.getMonth() + 1) + '-' + to.getDate();

        console.log('-----------------------');
        console.log( 'From: ' + from);
        console.log( 'To: ' + to);

        if(from != '' && to != '' && from <= to) {
            console.log('Correct date.');
            $http
            .get(API_URL + 'dashboard/' + from + '/' + to)
            .then(function (response) {
                $scope.users        = response.data.users;
                $scope.items        = response.data.items;
                $scope.inventories  = response.data.inventories;
                $scope.donors       = response.data.donors;
                $scope.itemStatus   = response.data.itemStatus;
                $scope.transactions = response.data.transactions;

                $scope.good_items = $filter('filter')($scope.itemStatus, { name: 'Good'}, true)[0];
                $scope.sold_items = $filter('filter')($scope.itemStatus, { name: 'Sold'}, true)[0];
            });  
        }
        else {
            console.log('Invalid date!');
        }  
        
    }
        
});


