<?php  
session_start();
include('conf.php');

try
{  
    $connect = new PDO("mysql:host=$host; dbname=$database", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(isset($_POST["login"]))
    {
        if(empty($_POST["username"]) || empty($_POST["password"]))
        {
            $message = '<label>All fields are required</label>';
        }
        else
            {
                $query = "SELECT * FROM users WHERE username = :username AND password = :password";
                $statement = $connect->prepare($query);
                $statement->execute(
                    array(
                        'username'     =>     $_POST["username"],
                        'password'     =>     $_POST["password"]
                    )
                );
                $count = $statement->rowCount();
                if($count > 0)
                {
                     $_SESSION["username"] = $_POST["username"];
                     header("location:categorytree.php");
                }
                else
                {
                     $message = '<label>Wrong Data</label>';
            }
        }
    }
}
catch(PDOException $error)
{
    $message = $error->getMessage();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="container">
            <div class="sub-container">
                <div class="login-form">
                    <form method="post">
                        <div class="title">
                            Login form
                        </div>
                        <div class="input-fields">
                            <label class="label">Username</label>
                            <input class="input" type="text" name="username">
                        </div>
                        <div class="input-fields">
                            <label class="label">Password</label>
                            <input class="input" type="password" name="password">
                        </div>
                        <div class="error">
                            <?php
                            if(isset($message))
                            {  
                                echo '<i>'.$message.'</i>';
                            }
                            ?>
                        </div>
                        <input type="submit" name="login" value="Login">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>