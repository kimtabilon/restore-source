app.controller('itemsController', function($scope, $http, API_URL) {
    //retrieve items listing from API
    $http.get(API_URL + "items")
            .then(function(response) {
                $scope.items = response.data;
            });
            // .success(function(response) {
            //     $scope.items = response;
            //     console.log(response);
            // });
    
    //show modal form
    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Add New Item";
                $scope.item = {name: '', description: ''};
                break;
            case 'edit':
                $scope.form_title = "Item Detail";
                $scope.id = id;
                $http.get(API_URL + 'items/' + id)
                        .then(function(response) {
                            console.log(response);
                            $scope.item = response.data;
                        });
                break;
            default:
                break;
        }
        console.log(id);
        $('#myModal').modal('show');
    }

    //save new record / update existing record
    $scope.save = function(modalstate, id) {
        var url = API_URL + "items";
        
        //append item id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/" + id;
        }
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.item),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            console.log(response);
            location.reload();
        }).error(function(response) {
            console.log(response);
            alert('This is embarassing. An error has occured. Please check the log for details');
        });
    }

    //delete record
    $scope.confirmDelete = function(id) {
        var isConfirmDelete = confirm('Are you sure you want this record?');
        if (isConfirmDelete) {
            $http({
                method: 'DELETE',
                url: API_URL + 'items/' + id
            }).
                    success(function(data) {
                        console.log(data);
                        location.reload();
                    }).
                    error(function(data) {
                        console.log(data);
                        alert('Unable to delete');
                    });
        } else {
            return false;
        }
    }
});