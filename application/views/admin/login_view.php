<!DOCTYPE html>
<html lang="en" ng-app="AdminLoginApp">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
</head>
<body ng-controller="AdminLoginController" class="bg-light">

<div class="container mt-5" style="max-width: 400px;">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="text-center mb-4">Admin Login</h3>

            <form ng-submit="login()">
                <div class="form-group mb-3">
                    <label>Email:</label>
                    <input type="email" ng-model="email" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>Password:</label>
                    <input type="password" ng-model="password" class="form-control" required>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary">Login</button>
                </div>
            </form>

            <div class="mt-3" ng-if="error">
                <div class="alert alert-danger">{{ error }}</div>
            </div>
        </div>
    </div>
</div>

<script>
    var app = angular.module('AdminLoginApp', []);

    app.controller('AdminLoginController', function($scope, $http) {
        $scope.email = '';
        $scope.password = '';
        $scope.error = '';

        $scope.login = function() {
            $http.post('<?= base_url("admin/login") ?>', {
                email: $scope.email,
                password: $scope.password
            }).then(function(res) {
                if (res.data.status === 'success') {
                    window.location.href = "<?= base_url("admin/dashboard") ?>";
                } else {
                    $scope.error = res.data.message || "Login failed.";
                }
            }, function() {
                $scope.error = "Request failed. Try again.";
            });
        };
    });
</script>

</body>
</html>
