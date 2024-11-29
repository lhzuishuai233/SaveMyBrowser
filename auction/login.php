<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Login</title>
</head>
<body>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="modal-content mt-5">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Login</h4>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <form method="POST" action="login_result.php">
            <div class="form-group">
              <label for="username">Email</label>
              <input type="text" class="form-control" name="username" id="username" placeholder="Email">
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary form-control">Sign in</button>
          </form>
          <div class="text-center mt-3">or <a href="register.php">create an account</a></div>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>
