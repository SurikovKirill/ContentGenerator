<?php

session_start();

$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'root';
$db_database = 'news_portal'; 
 // Create connection
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_database);

function get_snippet( $str, $wordCount = 10 ) {
    return implode( 
      '', 
      array_slice( 
        preg_split(
          '/([\s,\.;\?\!]+)/', 
          $str, 
          $wordCount*2+1, 
          PREG_SPLIT_DELIM_CAPTURE
        ),
        0,
        $wordCount*2-1
      )
    );
}

if ($_POST["submit_news"])
{
  
   if ($_POST["news_title"] == "" || $_POST["news_text"] == "")
   {
        $message = "<p class='text-center' >Заполните все поля!</p>";
   }
   else
   {
       $small_text = get_snippet($_POST["news_text"]);
       $small_text .= "...";
       mysqli_query($link,"INSERT INTO news (title, small_text, text)
                       VALUES ('".$_POST["news_title"]."','$small_text','".$_POST["news_text"]."')"); 

        header('Location: http://localhost:81/index.php');
   }  
 
}

$id = $_GET["id"];
$action = $_GET["action"];
if (isset($action)){
    $delete = mysqli_query($link, "DELETE FROM news WHERE id='$id'");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="fancybox/jquery.fancybox.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="fancybox/jquery.fancybox.js"></script>    
    <script type="text/javascript" src="js/script.js"></script>

    <title>NEWS</title>
</head>


<body>

<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-secondary shadow-sm">
        <?php
            $all_count = mysqli_query($link, "SELECT * FROM news");
            $result_count = mysqli_num_rows($all_count);
            if ($result_count == ""){
                $result_count = 0;
            }
        ?>
    <a href="index.php" class="my-0 mr-md-auto font-weight-normal text-dark"><h5>News: <?php echo $result_count; ?></h5></a>
    <a class="news btn btn-dark mx-2" href="#news" >Add news</a>
</div>
<div class="container">
<div class="d-flex flex-wrap">
    <?php

         if ($message != "") echo $message;

        $result = mysqli_query($link, "SELECT * FROM news ORDER BY id DESC");
    
        if (mysqli_num_rows($result) > 0)
        {
            $row = mysqli_fetch_array($result);
            do
            { 
                echo '
                        <div id="block-news" class="card text-center mt-3">
                            <h3>'.$row["title"].'</h3>
                            <p>'.$row["small_text"].'</p>
                            <a id="more" class="more btn btn-primary mt-auto" rel="news.php?id='.$row["id"].'" >More</a>
                            <a id="delete" name="'.$row["id"].'"  class="delete btn btn-danger" rel="index.php?id='.$row["id"].'&action=delete" >Delete</a>
                        </div>
                        ';
                }
         while ($row = mysqli_fetch_array($result));
        }	
    ?>
</div>
</div>
<div id="news">
 
    <form method="post">
        <div id="block-input">
            <label>Title <input type="text" name="news_title" /></label>
            <label>Full text <textarea name="news_text" ></textarea></label>
        </div>
        <p class="text-right">
            <input type="submit" name="submit_news" id="submit_news" value="Add news" />
        </p>
    </form>
 
</div>
    
</body>
</html>