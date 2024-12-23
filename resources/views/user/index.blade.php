<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <!-- Welcome Message -->
        <div class="text-center mb-4">
            <marquee behavior="" direction="" class="text-primary">
                <h1>Welcome, <b>{{ $user->name }}</b>!</h1>
            </marquee>
        </div>

        <!-- User Section -->
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-header bg-info text-white text-center">
                <h2>User Dashboard</h2>
            </div>
            <div class="card-body text-center">
                <a href="{{ route('change_password') }}" class="btn btn-warning me-3">Change Password</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional, for interactivity) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
