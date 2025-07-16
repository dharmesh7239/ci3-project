<!DOCTYPE html>
<html lang="en" ng-app="SuperadminApp">
<head>
    <meta charset="UTF-8">
    <title>Superadmin Panel</title>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            padding: 20px;
        }
        .sidebar h4 {
            color: #fff;
        }
        .sidebar a {
            display: block;
            color: #ddd;
            margin: 10px 0;
            text-decoration: none;
        }
        .sidebar a:hover {
            color: #fff;
            text-decoration: underline;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .circle-box {
            width: 100%;
            height: 180px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>
<body ng-controller="SuperadminController">

<div class="sidebar">
    <h4>Superadmin</h4>
    <a href="" ng-click="view='dashboard'">Dashboard</a>
    <a href="" ng-click="view='userList'">User List</a>
    <a href="" ng-click="view='addUser'">Add User</a>
    <a href="" ng-click="view='adminList'">Admin List</a>
    <a href="" ng-click="view='addAdmin'">Add Admin</a>
    <hr>
    <button class="btn btn-sm btn-danger w-100" ng-click="logout()">Logout</button>
</div>

<div class="content">

  
   <div ng-show="view === 'dashboard'">
    <h3>Welcome, Superadmin</h3>
    <div class="row mt-4">
        <div class="col-md-4 offset-md-2">
            <div class="circle-box bg-primary text-white text-center shadow">
                <div>{{ (users | filter:{role:'user'}).length }}</div>
                <div style="font-size: 1rem; font-weight: normal;">Total Users</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="circle-box bg-success text-white text-center shadow">
                <div>{{ (users | filter:{role:'admin'}).length }}</div>
                <div style="font-size: 1rem; font-weight: normal;">Total Admins</div>
            </div>
        </div>
    </div>
</div>


    <div ng-show="view === 'userList'">
        <h3>User List</h3>
        <div class="mb-3">
            <a href="<?= base_url('superadmin/export_users_pdf/user') ?>" class="btn btn-primary">Export PDF</a>
        </div>
        <table class="table table-bordered" ng-if="users.length">
            <tr>
                <th>Name</th><th>Email</th><th>Status</th><th>Action</th>
            </tr>
            <tr ng-repeat="u in users | filter:{role:'user'}">
                <td>{{ u.name }}</td>
                <td>{{ u.email }}</td>
                <td>{{ u.status }}</td>
                <td>
                    <button class="btn btn-warning btn-sm" ng-click="toggleStatus(u.id)">Status </button>
                    <button class="btn btn-info btn-sm" ng-click="changeRole(u.id, 'admin')">Make Admin</button>
                </td>
            </tr>
        </table>
        <p ng-if="!(users | filter:{role:'user'}).length">No users found.</p>
    </div>

 
    <div ng-show="view === 'adminList'">
        <h3>Admin List</h3>
        <div class="mb-3">
            <a href="<?= base_url('superadmin/export_users_pdf/admin') ?>" class="btn btn-primary">Export</a>
        </div>
        <table class="table table-bordered" ng-if="users.length">
            <tr>
                <th>Name</th><th>Email</th><th>Status</th><th>Action</th>
            </tr>
            <tr ng-repeat="u in users | filter:{role:'admin'}">
                <td>{{ u.name }}</td>
                <td>{{ u.email }}</td>
                <td>{{ u.status }}</td>
                <td>
                    <button class="btn btn-warning btn-sm" ng-click="toggleStatus(u.id)">Status</button>
                    <button class="btn btn-info btn-sm" ng-click="changeRole(u.id, 'user')">Make User</button>
                </td>
            </tr>
        </table>
        <p ng-if="!(users | filter:{role:'admin'}).length">No admins found.</p>
    </div>

 
    <div ng-show="view === 'addUser'">
        <h3>Add User</h3>
        <form ng-submit="createUser('user')" class="w-50">
            <input type="text" class="form-control mb-2" placeholder="Name" ng-model="form.name" required>
            <input type="email" class="form-control mb-2" placeholder="Email" ng-model="form.email" required>
            <input type="password" class="form-control mb-2" placeholder="Password" ng-model="form.password" required>
            <button class="btn btn-success">Create User</button>
        </form>
    </div>

  
    <div ng-show="view === 'addAdmin'">
        <h3>Add Admin</h3>
        <form ng-submit="createUser('admin')" class="w-50">
            <input type="text" class="form-control mb-2" placeholder="Name" ng-model="form.name" required>
            <input type="email" class="form-control mb-2" placeholder="Email" ng-model="form.email" required>
            <input type="password" class="form-control mb-2" placeholder="Password" ng-model="form.password" required>
            <button class="btn btn-primary">Create Admin</button>
        </form>
    </div>

</div>

<script>
var app = angular.module('SuperadminApp', []);
app.controller('SuperadminController', function($scope, $http) {
    $scope.users = [];
    $scope.view = 'dashboard';
    $scope.form = {};

    function loadUsers() {
        $http.get('<?= base_url("superadmin/get_users") ?>').then(function(res) {
            $scope.users = res.data;
        });
    }
    loadUsers();

    $scope.toggleStatus = function(id) {
        $http.post('<?= base_url("superadmin/toggle_status") ?>/' + id).then(loadUsers);
    };

    $scope.changeRole = function(id, newRole) {
        $http.post('<?= base_url("superadmin/change_role") ?>/' + id + '/' + newRole).then(loadUsers);
    };

    $scope.createUser = function(role) {
        const payload = {
            name: $scope.form.name,
            email: $scope.form.email,
            password: $scope.form.password,
            role: role
        };

        $http.post('<?= base_url("auth/register") ?>', payload).then(function(res) {
            alert(res.data.message);
            $scope.form = {};
            $scope.view = role === 'admin' ? 'adminList' : 'userList';
            loadUsers();
        }, function(err) {
            console.error("Failed to create", err);
            alert("Creation failed.");
        });
    };

    $scope.logout = function() {
        $http.get('<?= base_url("superadmin/logout") ?>').then(function() {
            window.location.href = "<?= base_url('superadmin') ?>";
        }, function(err) {
            alert("Logout failed.");
        });
    };
});
</script>

</body>
</html>
