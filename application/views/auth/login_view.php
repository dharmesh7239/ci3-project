<html lang="en" ng-app="AuthApp">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
</head>
<body ng-controller="AuthController">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">Login</div>
                <div class="card-body">
                    <form ng-submit="login()">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" ng-model="email" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" ng-model="password" required>
                        </div>
                        <button class="btn btn-primary btn-block" type="submit">Login</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var app = angular.module('AuthApp', []);
app.controller('AuthController', function($scope, $http) {
    $scope.login = function() {
        $http.post('<?= base_url("auth/login") ?>', {
            email: $scope.email,
            password: $scope.password
        }).then(function(response) {
            if (response.data.status === 'success') {
                window.location.href = '<?= base_url("project") ?>';
            } else {
                alert(response.data.message);
            }
        });
    };
});
</script>
</body>
</html>