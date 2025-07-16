<!DOCTYPE html>
<html lang="en" ng-app="SuperadminApp">
<head>
    <meta charset="UTF-8">
    <title>Superadmin Login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body ng-controller="SuperadminLoginController">

<div class="container mt-5">
    <h2>Superadmin Login</h2>

    <form ng-submit="login()" novalidate>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" ng-model="email" class="form-control" required>
        </div>

        <div class="form-group mt-2">
            <label>Password:</label>
            <input type="password" ng-model="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Login</button>
    </form>

    <div class="mt-3" ng-if="message">
        <div class="alert alert-danger">{{ message }}</div>
    </div>
</div>

<script>
    var app = angular.module('SuperadminApp', []);
    
    app.controller('SuperadminLoginController', function($scope, $http) {
        $scope.email = '';
        $scope.password = '';
        $scope.message = '';

        $scope.login = function() {
            $http.post('<?= base_url("superadmin/login") ?>', {
                email: $scope.email,
                password: $scope.password
            }).then(function(response) {
                if (response.data.status === 'success') {
                    window.location.href = '<?= base_url("superadmin/dashboard") ?>';
                } else {
                    $scope.message = response.data.message;
                }
            }, function(error) {
                console.error("Login request failed", error);
                $scope.message = 'Login request failed.';
            });
        };
    });
</script>

</body>
</html>
