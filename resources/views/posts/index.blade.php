<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Posts</title>

    <style>
        .form-group{
            margin-bottom: 18px;
        }
        .form-group .form-label{
            font-weight: 600;
        }
    </style>
</head>

<body class="bg-secondary">

    <div class="posts-page py-5">
        <div class="container">
            <h1 class="text-center text-white mb-4">Livewire Posts Crud Operation</h1>

            @livewire('post')
        </div>
    </div>

</body>
</html>
