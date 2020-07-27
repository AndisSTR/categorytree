<?php
session_start();
include('conf.php');

if(isset($_SESSION["username"]))
{
    echo '<div class="topnav" id="myTopnav">
    <a href="logout.php" class="active">Logout</a>
    <a href="categorytree.php">Catalog Tree</a>
    <a href="#index">Home</a>
    <a href="javascript:void(0);" class="icon" onclick="navOnClick()"><b style="font-size:15px">&#9776;</b></a>
    </div>';
}
else
{
    header("location:login.php");  
}

function addData($parent_id,$name,$description)
{
    try
    {
        include('conf.php');
        $connect = new PDO("mysql:host=$host; dbname=$database", $username, $password);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $statement = $connect->prepare("INSERT INTO categories
        (parent_id,name,description) VALUES(:parent_id, :name, 
        :description)");
        $statement->bindparam(":parent_id",$parent_id);
        $statement->bindparam(":name",$name);
        $statement->bindparam(":description",$description);
        $statement->execute();
        return true;
    }
    catch(PDOException $error)
    {
        $message = $error->getMessage(); 
        return false;
    } 
}
if(isset($_POST['btn-add']))
{
    $parent_id = $_POST['parent_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
 
    if(addData($parent_id,$name,$description))
    {
        header("Location: create.php?inserted");
    }
    else
    {
        header("Location: create.php?failure");
    }
}
?>

<script type="text/javascript">
    function navOnClick() {
        var x = document.getElementById("myTopnav");
        if (x.className === "topnav") {
                x.className += " responsive";
        } else {
            x.className = "topnav";
        }
    }
</script>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="container">
            <?php
                if(isset($_GET['inserted']))
                {
                ?>
                    <div class="container">
                        <div class="alert alert-info">
                            Record was inserted successfully!
                        </div>
                    </div>
                    <?php
                    }
                        else if(isset($_GET['failure']))
                    {
                    ?>
                    <div class="container">
                        <div class="alert alert-warning">
                            ERROR while inserting record !
                        </div>
                    </div>
                <?php
                }
            ?>
            <form method="post">
                <div class="table-container">
                    <table class="table">
                        <tr>
                            <td class="title" colspan="2">Add Category</td>
                        </tr>
                        <tr>
                            <td class="name" height=60px;>Parent ID</td>
                            <td><input class="input" type='text' name='parent_id' required></td>
                        </tr>
                        <tr>
                            <td class="name" height=60px;>Name</td>
                            <td><input class="input" type='text' name='name' required></td>
                        </tr>
                        <tr>
                            <td class="name" height=60px;>Description</td>
                            <td><input class="input" type='text' name='description' required></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                            <input type="submit" name="btn-add" value="Create New Record">  
                            </td>
                        </tr>
                    </table>
                </div>
            </form>     
        </div>
    </body>
</html>