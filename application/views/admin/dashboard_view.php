<!DOCTYPE html>
<html lang="en" ng-app="AdminApp">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Dark Mode CSS and Toggle Button -->
    <link rel="stylesheet" href="/assets/css/dark-mode.css" id="dark-mode-css" disabled>
    <style>
        body { display: flex; }
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            padding: 20px;
        }
        .sidebar h4 { color: #fff; }
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
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: #0d6efd;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    font-weight: bold;
    font-size: 1.5rem;
}

    </style>
</head>
<body ng-controller="AdminController">

<div class="sidebar">
    <h4>Admin Panel</h4>
    <a href="" ng-click="view='dashboard'">Dashboard</a>
    <a href="" ng-click="view='userList'">User List</a>
    <a href="" ng-click="view='addUser'">Add User</a>
    <hr>
    <button class="btn btn-sm btn-danger w-100" ng-click="logout()">Logout</button>
    
</div>

<div class="content">
    <!-- Dashboard View -->
<div ng-show="view === 'dashboard'">
    <h3>Welcome, Admin</h3>
    <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
        <div class="circle-box text-center shadow">
            <h1>{{ users.length }}</h1>
            <p>Total Users</p>
        </div>
    </div>
</div>

    <div ng-show="view === 'userList'">
        <h3>User List</h3>
        <table class="table table-bordered" ng-if="users.length">
            <tr>
    <th>Name</th><th>Email</th><th>Status</th><th>Action</th>
</tr>
            <tr ng-repeat="u in users">
                <td>{{ u.name }}</td>
                <td>{{ u.email }}</td>
                <td>{{ u.status }}</td>
                <td>
        <button class="btn btn-sm btn-warning" ng-click="toggleStatus(u.id)">Toggle Status</button>
    </td>
            </tr>
        </table>
        <p ng-if="!users.length">No users found.</p>
    </div>

    <div ng-show="view === 'addUser'">
        <h3>Add User</h3>
        <form ng-submit="createUser()" class="w-50">
            <input type="text" class="form-control mb-2" placeholder="Name" ng-model="form.name" required>
            <input type="email" class="form-control mb-2" placeholder="Email" ng-model="form.email" required>
            <input type="password" class="form-control mb-2" placeholder="Password" ng-model="form.password" required>
            <button class="btn btn-success">Create User</button>
        </form>
    </div>

</div>

<script>
    var app = angular.module('AdminApp', []);
    app.controller('AdminController', function($scope, $http) {
       $scope.view = 'dashboard'; 
        $scope.form = {};
        $scope.users = [];

        function loadUsers() {
            $http.get('<?= base_url("admin/get_users") ?>').then(function(res) {
                $scope.users = res.data;
            });
        }

        loadUsers();

        $scope.createUser = function() {
            const payload = {
                name: $scope.form.name,
                email: $scope.form.email,
                password: $scope.form.password,
                role: 'user'
            };

            $http.post('<?= base_url("auth/register") ?>', payload).then(function(res) {
                alert(res.data.message);
                $scope.form = {};
                $scope.view = 'userList';
                loadUsers();
            }, function(err) {
                alert("User creation failed.");
                console.error("Creation error:", err);
            });
        };

        $scope.logout = function() {
            $http.get('<?= base_url("admin/logout") ?>').then(function() {
                window.location.href = "<?= base_url("admin") ?>";
            });
        };


        $scope.toggleStatus = function(id) {
    $http.post('<?= base_url("admin/toggle_status") ?>/' + id).then(function(res) {
        loadUsers(); 
    }, function(err) {
        console.error("Toggle failed", err);
        alert("Toggle status failed.");
    });
};

    });
</script>

<button class="dark-mode-toggle" id="darkModeToggle">🌙 Dark Mode</button>
<script>
  const darkModeCss = document.getElementById('dark-mode-css');
  const toggleBtn = document.getElementById('darkModeToggle');
  function setDarkMode(enabled) {
    if (enabled) {
      darkModeCss.removeAttribute('disabled');
      document.body.classList.add('dark-mode');
      toggleBtn.textContent = '☀️ Light Mode';
    } else {
      darkModeCss.setAttribute('disabled', 'true');
      document.body.classList.remove('dark-mode');
      toggleBtn.textContent = '🌙 Dark Mode';
    }
  }
  // On load
  const darkPref = localStorage.getItem('darkMode') === 'true';
  setDarkMode(darkPref);
  toggleBtn.onclick = () => {
    const enabled = darkModeCss.hasAttribute('disabled');
    setDarkMode(enabled);
    localStorage.setItem('darkMode', enabled);
  };
</script>

</body>
</html>
