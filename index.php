<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Enrolment System</title>
</head>

<body>
    <?php
    include('server.php');
    ?>
    <div class="titleContainer">
        <h1>Subject Enrolment System</h1>
    </div>
    <div class="pageContainer">
        <div class="column">
            <div class="head">
                <h1 class="title"> Log In</h1><br>
                <!-- <img class="logo"
                    src="https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fclipground.com%2Fimages%2Fmerlion-clipart-19.jpg&f=1&nofb=1&ipt=358ddcf59744dde1cfd38fab17b374b0239c171ec6b6f2b7d2f1e44be208aeab&ipo=images"
                    alt="Merlion"> -->
            </div>
            <form action='index.php' method='POST'>
                <div class="inputContainer">
                    <input class="email" type="email" name="email" placeholder="Email*" required>
                    <input class="password" type="password" name="password" placeholder="Password*" required>
                </div>
                <div class="submitResetContainer">
                    <div class="submitResetItem resetContainer">
                        <button type='reset' name='reset' value='Reset'>Reset</button>
                    </div>
                    <div class="submitResetItem submitContainer">
                        <button type='submit' name='login' value='Log In'>Log In</button>
                    </div>
                </div>
                <div class="wrap">
                    <p class="reg-text">Not registered yet? <span><a href="register.php">Register Here</a></span></p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>