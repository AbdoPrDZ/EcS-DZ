<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin - Auth</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/sass/app.scss', 'resources/js/admin/auth/app.js'])
</head>
<body>
  <div id="auth-app">
    <b-container>
      <b-card style="margin: 30px auto; max-width: 40rem;" class="mt-5">
        <router-view></router-view>
      </b-card>
    </b-container>
  </div>
</body>
</html>
