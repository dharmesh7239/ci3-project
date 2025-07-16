<form ng-submit="saveProject()" novalidate>
    <div class="form-group mb-3">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" ng-model="project.name" required>
    </div>

    <div class="form-group mb-3">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" rows="3" ng-model="project.description" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Save Project</button>
</form>


