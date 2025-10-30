<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'OneHelp PH')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  @include('components.navbar')

  <main>
    @yield('content')
  </main>

  <footer class="text-center py-4" style="background-color:#FBF9F1;">
    <p class="fw-bold mb-1">OneHelp PH</p>
    <p class="text-muted small mb-0">© 2025 OneHelp Philippines | <a href="#">Privacy Policy</a></p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
