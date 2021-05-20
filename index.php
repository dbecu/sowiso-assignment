<?php
session_start();

if (isset($_POST['restart'])) 
{
    $_SESSION["good-ans"] = 0;

}

$_SESSION["status"] = "new";

// Deciding the status of the answer
if (isset($_POST['submit']) && !empty($_POST['answer'])) 
{
    $userAnswer = $_POST['answer'];
    $userAnswerType = gettype($userAnswer);
    if ($userAnswer == "integer" || $userAnswer == "double")
    {
        //... wrong input type
        $_SESSION["status"] = "badinput";
    }
    else
    {
        $equation = $_SESSION["equation"];
        $correctAnswer = explode("=", $equation)[1];
       
        if ($userAnswer == $correctAnswer)
        {
            // echo "goo?d job!";
            $_SESSION["status"] = "correct";

            if (isset($_SESSION["good-ans"]))
            {
                $_SESSION["good-ans"] = $_SESSION["good-ans"] + 1;
            }
            else
            {
                $_SESSION["good-ans"] = 0;
            }
        }
        else
        {
            // echo "too bad";
            $_SESSION["status"] = "wrong";
        }
    }
}

if(isset($_POST['next'])) $_SESSION["status"] = "new";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Assignment</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
  <!-- styles -->
  <style>
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }

    .jumbotron {
      background-color: #6da870;
      color: #fff;
      padding: 100px 25px;
      margin: 0;
    }

    .container-fluid {
      padding: 60px 50px;
    }

    .bg-grey {
      background-color: #f6f6f6;
    }

    .logo-small {
      color: #f4511e;
      font-size: 50px;
    }

    .logo {
      color: #6da870;
      font-size: 200px;
    }

    #nav_logo {
      padding: 5px 20px;
      text-align: center;
      font-size: 35px;
    }

    form {
      padding: 0;
      border: 0;
      margin: 0 0 0;
    }

    .bg-4 {
      background-color: #2f2f2f;
      /* Black Gray */
      color: #fff;
    }

    @media screen and (max-width: 768px) {
      .col-sm-4 {
        text-align: center;
        margin: 25px 0;
      }
    }
  </style>
</head>

<!-- content -->
<body>

  <!-- header start -->
  <div class="jumbotron text-center">
    <h1>SOWISO application</h1>
    <p>Maths</p>
    <p>Correct answers: <?php echo $_SESSION["good-ans"]?></p>
    <p>Difficulty increases as you get more correct answers :)</p>
  </div>
  <!-- header end -->

  <!-- nav bar start -->
  <nav class="navbar navbar-inverse" id="thenavbar">
    <div class="container-nav">

      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="glyphicon glyphicon-asterisk logo" id="nav_logo" href="#"></a>
      </div>

      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li class="active"><a href="index.php">Addition</a></li>
        </ul>
      </div>

    </div>
  </nav>
  <!-- nav bar end -->


  <!-- start about section -->
  <div class="container-fluid">
    <div class="row">

      <div class="col-sm-8">
        <h2>Addition</h2>

        <?php

        // checks if the prev answer was correct and gives feedback 
        if ($_SESSION["status"] == "correct" || $_SESSION["status"] == "wrong" || $_SESSION["status"] == "badinput")
        {
            $userAnswer = sprintf("%s = %s", explode("=", $_SESSION["equation"])[0],  $_POST['answer']);

            switch($_SESSION["status"])
            {
                case "correct":
                    echo sprintf(("<h5>%s is correct!</h5>"),  $userAnswer);
                    break;
                case "wrong":
                    echo sprintf(("<h5>%s is wrong!</h5>"),  $userAnswer);
                    break;
                case "badinput":
                    echo sprintf(("<h5>%s has a bad input!</h5>"),  $userAnswer);
                    break;
            }
        }

        if (session_status() === PHP_SESSION_NONE) session_start();

        // providing the equqations
        $num1 = 0;
        $num2 = rand(1, 5);

        //checks to show previous equation or not
        if ($_SESSION["status"] != "new")
        {
            $prevEquation = explode('+', $_SESSION["equation"]);
            $num1 = $prevEquation[0];
            $num2 = explode("=", $prevEquation[1])[0];
        }
        else
        {
            $correctAnswersCount = $_SESSION["good-ans"];

            $num1 = rand(1 + $correctAnswersCount, 10 + $correctAnswersCount * 10);
            $num2 = rand(1 + $correctAnswersCount, 10 + $correctAnswersCount * 10);
        }
 

        $_SESSION["equation"] = sprintf("%d+%d=%d", $num1, $num2, $num1 + $num2);

        echo "<h4> $num1 + $num2 = </h4>";
        ?>

        <!-- Submitting the answer -->
        <form class="form-horizontal" action="" method="post">
            <input type="text" id="answer" name="answer" value=""><br>
            <input type="submit" value="Submit" name="submit">
        </form>

        <p>
        
        <?php
        $response = "Good luck!";

        // Personalized text depending on how the user answers prev
        if (isset($_SESSION["status"]))
        {
            switch($_SESSION["status"])
            {
                case "correct":
                    $response = "Nice! Go to next question?";
                    break;
                case "wrong":
                    $response = "Too bad! New question?";
                    break;
                case "badinput":
                    $response = "Wrong input :\ New question?";
                    break;
            }
        }
        echo $response;
        ?>
        <p>

        <!-- To go to other equations -->
        <form class="form-horizontal" action="" method="post">
            <input type="submit" value="Yes" name="next">
        </form>

        <form class="form-horizontal" action="" method="post">
            <input type="submit" value="Restart Session" name="restart">
        </form>

      </div>
      <div class="col-sm-4">
        <span class="glyphicon glyphicon-plus logo"></span>
      </div>

    </div>
  </div>
  <!-- end about section -->

</body>

</html>

<?php
ob_end_flush();
?>