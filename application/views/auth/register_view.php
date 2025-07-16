
<!DOCTYPE html>
<html lang="en" ng-app="ProjectApp">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
</head>
<body ng-controller="RegisterController">

<div class="container mt-5">
    <h2>Register</h2>

    <form ng-submit="register()" novalidate>
        <div class="form-group">
            <label>Name:</label>
            <input type="text" ng-model="user.name" class="form-control" required>
        </div>

        <div class="form-group mt-2">
            <label>Email:</label>
            <input type="email" ng-model="user.email" class="form-control" required>
        </div>

        <div class="form-group mt-2">
            <label>Password:</label>
            <input type="password" ng-model="user.password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Register</button>
    </form>

    <div class="mt-3" ng-if="responseMessage">
        <div class="alert" ng-class="{'alert-success': success, 'alert-danger': !success}">
            {{ responseMessage }}
        </div>
    </div>

    <div class="mt-2">
        <a href="<?= base_url('login') ?>">Already have an account? Login</a>
    </div>
</div>

<script>
var app = angular.module('ProjectApp', []);

app.controller('RegisterController', function($scope, $http) {
    $scope.user = {
        name: '',
        email: '',
        password: '',
        role: 'user'
    };
    $scope.success = null;
    $scope.responseMessage = '';

    $scope.register = function() {
        $http.post('<?= base_url("auth/register") ?>', $scope.user, {
            headers: { 'Content-Type': 'application/json' }
        }).then(function(response) {
            const res = response.data;
            if (res.status === 'success') {
                $scope.success = true;
                $scope.responseMessage = res.message;
                // redirect to login
                setTimeout(function() {
                    window.location.href = "<?= base_url('login') ?>";
                }, 1000);
            } else {
                $scope.success = false;
                $scope.responseMessage = res.errors || res.message || "Registration failed.";
            }
        }, function(error) {
            $scope.success = false;
            $scope.responseMessage = "Request failed.";
            console.error("Registration error:", error);
        });
    };
});
</script>

</body>
</html>
