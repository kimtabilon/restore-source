app.controller('usersController', function($scope, $http, $location, $filter, API_URL) { 
	
    $http
    .get(API_URL + 'users/profile')
    .then(function (response) {
    	$scope.profile = response.data;
    });

    $http
    .get(API_URL + 'users')
    .then(function (response) {
        $scope.users = response.data.users;
        $scope.roles = response.data.roles;
    });


    $scope.toggle = function(user, action) {
        switch (action) {
            case 'new':              
                $scope.modal = {
                    title: "New User",
                    array : { role: $scope.roles },
                    field: { 
                        given_name  : 'Given Name', 
                        middle_name : 'Middle Name', 
                        last_name   : 'Last Name', 
                        // username    : 'Username', 
                        email       : 'Email', 
                        password    : 'Password', 
                        role        : 'Role',
                    },
                    data: { 
                        action      : action,
                        given_name  : '', 
                        middle_name : '', 
                        last_name   : '', 
                        // username    : '', 
                        email       : '',
                        password    : 'secret',
                        role        : '',
                    },
                    button: 'Create'
                };  
                break;
            case 'edit':
                var index  = $scope.users.indexOf(user);

                $scope.modal = {
                    title: "Modify User",
                    array : { role: $scope.roles },
                    field: { 
                        given_name  : 'Given Name', 
                        middle_name : 'Middle Name', 
                        last_name   : 'Last Name', 
                        // username    : 'Username', 
                        email       : 'Email', 
                        password    : 'New Password', 
                        role        : 'Role',
                    },
                    data: { 
                        action      : action,
                        given_name  : user.given_name, 
                        middle_name : user.middle_name, 
                        last_name   : user.last_name, 
                        // username    : user.username, 
                        email       : user.email,
                        password    : '',
                        role        : user.role.name,
                        id          : user.id
                    },
                    button: 'Save changes'
                };  
                break;

            default:
                break;
        }
        $('#userModal').modal('show');
    }

    $scope.update = function(user) {
        
        switch (user.action) {
            case 'new':
                $http({
                    method  : 'POST',
                    url     : API_URL + 'users/save',
                    data    : user
                })
                .then(function (response) {
                    $scope.users.push(response.data);
                });
                break;

            case 'edit':
                user.middle_name = user.middle_name!=null?user.middle_name:''; 
                $http({
                    method  : 'POST',
                    url     : API_URL + 'users/save',
                    data    : user
                })
                .then(function (response) {
                    var match = $filter('filter')($scope.users, {id: user.id}, true)[0];
                    var index = $scope.users.indexOf(match);
                    $scope.users[index] = response.data;
                });
                break;    

            default:
                break;
        }
        $('#userModal').modal('hide');

    }

    $scope.remove = function(user) {
        if(confirm('Remove user?'))
        {
            $http({
                method  : 'POST',
                url     : API_URL + 'users/destroy',
                data    : user
            })
            .then(function (response) {
                var index = $scope.users.indexOf(user);
                $scope.users.splice(index, 1);
            });
        }
        
    }

});


