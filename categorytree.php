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

if(isset($_GET["del"]))
{
	$id=$_GET["del"];
    
    $connect = new PDO("mysql:host=$host; dbname=$database", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $get_parent_id = $connect->prepare("SELECT parent_id FROM categories WHERE id=:id");
    $get_parent_id->bindParam(':id',$id);
    $get_parent_id->execute();
    $parent = $get_parent_id->fetch(PDO::FETCH_ASSOC);
    $parent_id = $parent['parent_id'];
    
    $delete = $connect->prepare("DELETE FROM categories WHERE id=:id");
    $delete->bindParam(':id',$id);
    $delete->execute();
    
    header("Location:categorytree.php");	
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
                try {
                    function CategoryTree(&$output=null, $parent=0, $indent=null){
                        include('conf.php');
                        $connect = new PDO("mysql:host=$host; dbname=$database", $username, $password);
                        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                        $r = $connect->prepare("SELECT * FROM categories WHERE parent_id=:parentid");
                        $r->execute(array(
                            'parentid' 	=> $parent));
                        while($c = $r->fetch(PDO::FETCH_ASSOC)){
                            $output .= '<li class="table-row"><div class="col col-1">' . $indent . 
                                '<b class="symbol">&#10132;</b> ' . $c['name'] . 
                                '</div><div class="col col-2">' . $c['description'] . 
                                '</div><div class="col col-3">
                                <a style="background-color: #505050; font-size:14px; margin: 2px; padding: 2px 4px; display: inline-block; text-decoration: none; color: #FFFFFF; border-radius: 3px; box-shadow: 0px 0px 9px 0px rgba(0, 0, 0, 0.2);" href="edit.php?id=' . $c['id'] . '">Edit</a>
                                <a style="background-color: #FF3366; font-size:14px; margin: 2px; padding: 2px 4px; display: inline-block; text-decoration: none; color: #FFFFFF; border-radius: 3px; box-shadow: 0px 0px 9px 0px rgba(0, 0, 0, 0.2);" href="?del=' . $c['id'] . '">Delite</a></div></li>';
                            if($c['id'] != $parent){
                                CategoryTree($output, $c['id'], $indent . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
                            }
                        }
                    return $output;
                    }
                    echo "<ul class='responsive-table'>" . CategoryTree() . "</ul>";
                    }
                    catch(PDOException $error) {
                        echo "Error: " . $error->getMessage();
                    }
                $connect = null;
                echo "";
            ?>
            <div align="right"><a style="background-color: #30E060; font-size:20px; margin: 2px; padding: 4px 8px; display: inline-block; text-decoration: none; color: #FFFFFF; border-radius: 3px; box-shadow: 0px 0px 9px 0px rgba(0, 0, 0, 0.2);" href="create.php">Add New Category</a></div>
        </div>
    </body>
</html>