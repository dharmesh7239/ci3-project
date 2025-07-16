<!DOCTYPE html>
<html lang="en" ng-app="ProjectApp">
<head>
    <meta charset="UTF-8">
    <title>Dharmesh Project</title>

    <!-- CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular-sanitize.min.js"></script>

    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.6;
        }
    </style>
</head>
<body ng-controller="ProjectController">

<!-- Modal -->
<div class="modal fade" id="projectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ modalTitle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalContentContainer"></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Container -->
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <strong>Welcome, {{ userName }} ({{ userEmail }})</strong><br>
            <span class="text-muted">Role: {{ userRole }}</span>
        </div>
        <button class="btn btn-outline-danger" ng-click="logout()">Logout</button>
    </div>

    <div class="card">
        <div class="card-header">
            <button class="btn btn-outline-primary" ng-click="openModal('create')">
                Create New Project
            </button>
        </div>

        <table class="table table-bordered" ng-if="projects.length">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th width="240px">Action</th>
            </tr>

            <tr ng-repeat="project in projects">
                <td>{{ project.name }}</td>
                <td>{{ project.description }}</td>
                <td>
                    <button class="btn btn-outline-info" ng-click="openModal('show', project.id)">Show</button>
                    <button class="btn btn-outline-success" ng-click="openModal('edit', project.id)">Edit</button>
                    <button class="btn btn-danger" ng-click="deleteProject(project.id)">Delete</button>
                </td>
            </tr>
        </table>

        <div ng-if="!projects.length" class="text-center">
            <em>No projects found.</em>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item" ng-class="{disabled: currentPage === 1}">
                        <a class="page-link" href="" ng-click="currentPage !== 1 && getData(currentPage - 1)">Previous</a>
                    </li>

                    <li class="page-item" ng-repeat="n in [].constructor(totalPages()) track by $index"
                        ng-class="{active: currentPage === ($index + 1)}">
                        <a class="page-link" href="" ng-click="getData($index + 1)">
                            {{$index + 1}}
                        </a>
                    </li>

                    <li class="page-item" ng-class="{disabled: currentPage === totalPages()}">
                        <a class="page-link" href="" ng-click="currentPage !== totalPages() && getData(currentPage + 1)">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- AngularJS App -->
<script>
    var app = angular.module('ProjectApp', ['ngSanitize']);

    app.controller('ProjectController', function($scope, $http, $sce, $compile) {
        $scope.title = "CodeIgniter Project Manager";
        $scope.projects = [];
        $scope.totalItems = 0;
        $scope.currentPage = 1;
        $scope.itemsPerPage = 5;
        $scope.project = {};

        $scope.getData = function(page = 1) {
            $scope.currentPage = page;
            let offset = (page - 1) * $scope.itemsPerPage;

            $http.get("<?= base_url('project/json_list') ?>/" + $scope.itemsPerPage + "/" + offset)
                .then(function(response) {
                    $scope.projects = response.data.projects;
                    $scope.totalItems = response.data.total;
                }, function(error) {
                    console.error("Error loading data:", error);
                });
        };

        $scope.totalPages = function() {
            return Math.ceil($scope.totalItems / $scope.itemsPerPage);
        };

        $scope.getData(1);

        $scope.userRole = '';
        $scope.userName = '';
        $scope.userEmail = '';

        $http.get('<?= base_url("auth/session_info") ?>').then(function(res) {
            $scope.userRole = res.data.role;
            $scope.userName = res.data.name;
            $scope.userEmail = res.data.email;
        }, function(err) {
            console.error("Failed to get session info", err);
        });

        $scope.deleteProject = function(id) {
            $http.delete("<?= base_url('project/delete/') ?>" + id)
                .then(function() {
                    $scope.getData($scope.currentPage);
                }, function(error) {
                    console.error("Error deleting project:", error);
                });
        };

        $scope.modalTitle = "";
        $scope.openModal = function(action, id = null) {
            let url = "";
            if (action === 'show') {
                $scope.modalTitle = "Project Details";
                url = "<?= base_url('project/show/') ?>" + id;
            } else if (action === 'edit') {
                $scope.modalTitle = "Edit Project";
                url = "<?= base_url('project/edit/') ?>" + id;
            } else if (action === 'create') {
                $scope.modalTitle = "Create New Project";
                url = "<?= base_url('project/create') ?>";
            }

            $http.get(url).then(function(response) {
                var container = angular.element(document.getElementById("modalContentContainer"));
                container.html(response.data);
                $compile(container.contents())($scope);

                var modal = new bootstrap.Modal(document.getElementById('projectModal'));
                modal.show();
            }, function(error) {
                console.error("Modal content load error:", error);
                alert("Could not load modal.");
            });
        };

        $scope.saveProject = function() {
            const formData = {
                name: $scope.project.name,
                description: $scope.project.description
            };

            $http({
                method: 'POST',
                url: "<?= base_url('project/store') ?>",
                data: $.param(formData),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function() {
                var modal = bootstrap.Modal.getInstance(document.getElementById('projectModal'));
                modal.hide();
                $scope.project = {};
                $scope.getData($scope.currentPage);
            }, function(error) {
                console.error("Failed to save:", error);
                alert("Error saving project.");
            });
        };

        $scope.updateProject = function(id) {
            const formData = {
                name: $scope.project.name,
                description: $scope.project.description
            };

            $http({
                method: 'POST',
                url: "<?= base_url('project/update/') ?>" + id,
                data: $.param(formData),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function() {
                var modal = bootstrap.Modal.getInstance(document.getElementById('projectModal'));
                modal.hide();
                $scope.project = {};
                $scope.getData($scope.currentPage);
            }, function(error) {
                console.error("Update failed:", error);
                alert("Failed to update project.");
            });
        };

            $scope.logout = function () {
            $http.get("<?= site_url('logout') ?>").then(function () {
                window.location.href = "<?= site_url('login') ?>";
            }, function (error) {
                console.error("Logout failed:", error);
                alert("Logout failed");
            });
        };

    });
</script>

</body>
</html>
