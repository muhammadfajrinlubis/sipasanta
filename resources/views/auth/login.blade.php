@guest
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Sistem Informasi Pengaduan</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Admin, Dashboard, Bootstrap" />
    <link rel="shortcut icon" sizes="196x196" href="favicon.ico">
    
    <link rel="stylesheet" href="{{url('assets-admin')}}/libs/bower/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{url('assets-admin')}}/libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="{{url('assets-admin')}}/libs/bower/animate.css/animate.min.css">
    <link rel="stylesheet" href="{{url('assets-admin')}}/assets/css/bootstrap.css">
    <link rel="stylesheet" href="{{url('assets-admin')}}/assets/css/core.css">
    <link rel="stylesheet" href="{{url('assets-admin')}}/assets/css/misc-pages.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300">
    <style>
        .position-relative {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 1.2em;
        }

        .form-control {
            padding-right: 2.5em;
        }
    </style>
</head>
<body class="simple-page">
    <div class="simple-page-wrap">
        <div class="simple-page-logo animated swing">
            <a href="index.html">
                <span><i class="fa fa-gg"></i></span>
                <span>SI PASAN</span>
            </a>
        </div><!-- logo -->
        <div class="simple-page-form animated flipInY" id="login-form">
            <h4 class="form-title m-b-xl text-center">Silahkan Login</h4>
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span>{{ session('error')}}</span>
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span>{{ session('success')}}</span>
            </div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input id="sign-in-email" autofocus type="text" name="username" class="form-control" placeholder="NIP Pegawai...">
                </div>

                <div class="form-group position-relative">
                    <input id="sign-in-password" type="password" name="password" class="form-control" placeholder="Password...">
                    <!-- Ikon untuk show/hide password -->
                    <i class="fa fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>

                <input type="submit" class="btn btn-primary" value="LOGIN">
            </form>
        </div><!-- #login-form -->
    </div><!-- .simple-page-wrap -->
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById('sign-in-password');
            var toggleIcon = document.querySelector('.toggle-password');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 3000);
    </script>
</body>
</html>
@endguest
@auth
  <script>window.location = "/admin/home";</script>
@endauth
