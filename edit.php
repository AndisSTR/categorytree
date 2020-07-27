<?php
session_start();
include('conf.php');

$connect = new PDO("mysql:host=$host; dbname=$database", $username, $password);
$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

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

$id = $_GET["id"];
if(isset($_POST['update']))
{
    $id = $_POST['id'];
    $parent_id = $_POST['parent_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    
    $statement = $connect->prepare("UPDATE categories SET parent_id=:parent_id, name=:name, description=:description WHERE id=:id");
    $update = $statement->execute(array(":parent_id"=>$parent_id, ":name"=>$name, ":description"=>$description, ":id"=>$id));
    
    if($update)
    {
        header('location: categorytree.php');
    }
    else
    {
        echo '<script>alert("Error!")</script>';
    }
}

$statement = $connect->prepare("SELECT * FROM categories where id=" . $_GET["id"]);
$statement->execute(array(':id' => $id));
$row = $statement->fetch(PDO::FETCH_ASSOC);

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
            <form method="post">
                <div class="table-container">
                    <table class="table">
                       <tr>
                            <td class="title" colspan="2">Edit</td>
                        </tr>
                        <tr>
                            <td class="name" height=60px;>Parent ID</td>
                            <td><input class="input" type='text' name='parent_id' value="<?= $row["parent_id"]; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="name" height=60px;>Name</td>
                            <td><input class="input" type='text' name='name' value="<?= $row["name"]; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="name" height=60px;>Description</td>
                            <td><input class="input" type='text' name='description' value="<?= $row["description"]; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="id" value="<?= $id; ?>"></td>
                            <td>
                            <input type="submit" name="update" value="Update"> 
                            </td>
                        </tr>
                    </table>
                </div>
            </form>     
        </div>
    </body>
</html>