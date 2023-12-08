<?php
    require 'connection.php';
    require 'app/data/UserDTO.php';
    require 'app/entities/UserModel.php';
    require 'app/entities/ColorModel.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">    
        <div class="row content">
            <div class="well">
                <div class="wrapper">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <?php 
                                    // extract the action parameter from the url
                                    
                                    if (isset($_GET['action']) && $_GET['action'] !='') {
                                        $action = $_GET['action'];
                                    } else {
                                        $action ='';
                                    }
                                    switch  ($action) {
                                        case  '':
                                            //default page that shows all the products
                                            require "view/list.php";
                                            break;
                                        case 'create':
                                            //page that handles the CREATE operation
                                            require "view/create.php";
                                            break;
                                        case 'edit':
                                            //page that handles the UPDATE operation
                                            include("view/update.php");
                                            break;
                                        case 'display':
                                            //page that handles the READ operation
                                            include("view/read.php");
                                            break;
                                        case 'remove':
                                            //page that handles the DELETE operation
                                            include("view/delete.php");
                                            break;
                                        default:
                                            //page that handles any error occured
                                            include("view/error.php");
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>