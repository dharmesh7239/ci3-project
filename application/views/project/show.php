<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container mt-5">
   
    <div class="card shadow-sm">
        
        <div class="card-body">
            <div class="mb-3">
                <h6 class="text-muted mb-1">Name</h6>
                <p class="mb-0"><?php echo $project->name; ?></p>
            </div>

            <div class="mb-3">
                <h6 class="text-muted mb-1">Description</h6>
                <p class="mb-0"><?php echo $project->description; ?></p>
            </div>
        </div>

       
    </div>
</div>


</body>
</html>
