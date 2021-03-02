<!DOCTYPE html>
<?php include("urlShortenerFunctions.php");?>
<!--
    Created by: Logan Crockett
    On: 8/16/20
    Purpose: It takes a URL Input from urlShortener.html and adds it to the database of URL's we have shortened
-->
<html lang="en">

    <head>
        <title>URL Shortner</title>
        <meta charset="utf-8">
    </head>

    <body>
        <!--Here we will check and see if we need to redirect to another page by checking if there is any
        input string after the ".php/" string-->
        <?php
        //Here we will check if the URL has any input after it
        //If so, then redirect to the correct page
        //If not, then we will add it to the database

        //Get the end of the current URL
        $currentURL = substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],"/")+1);//Add one so we do not include the slash in our Short URL search
        //print $currentURL;//For debugging purposes

        /*
        Check if the currentURL is just "urlShortener.php";
        In doing so, we are checking if we need to redirect or add a link
        If not, then we just need to redirect the URL; Otherwise, we can add it to the database
        */
        if ($currentURL != "urlShortener.php") {
            //We just need to find the link and redirect
            //IF the connection is not successful, the function will handle it
            $myConnection = connectToDatabase();
            print "<p>Successfully Connected to database.</p>";


            $longURL = checkForLongURL($myConnection,$currentURL);//Find the longURL based on the current one
            //print $longURL;//For debugging purposes

            //Check if the longURL is set; If not, then we have not added this link yet, and do not need to redirect
            if (isset($longURL)) {
                //Update the number of redirects performed on this link
                incrementRedirects($myConnection,$currentURL);

                //Here we can close the connection to the DB before proceeding
                $myConnection = null;//Setting it to null will close the connection since we used PDO

            //Now we can redirect to the correct link
            $completeURL = "http://".$longURL;//Build the URL before redirecting

            //Now redirect the page
            header("Location:".$completeURL,301);
            }
            //Otherwise, print a message stating that this link is invalid and has not been added
            else {
                //Here we can close the connection to the DB before proceeding
                $myConnection = null;//Setting it to null will close the connection since we used PDO

                //Output our error message here
                print "<p>This link is invalid. It has not been generated</p>";
                exit();
            }
        }
            
        //Othwerwise, we can proceed and add the URL to the database
        ?>


        <!--Here we will go through and add the URL to the database if it was not found-->


        <?php
        //We do not have to check if the input has a http or https preceding it; the form will validate it
        //Go ahead and fetch the URL
        $longURL = $_POST['longURL'];

        //If we are unable to get the URL, then output an error message
        if (!isset($longURL)) {
            print "<p>Error: Unable to retrieve URL from urlShortener.html<p>";
            print "<p>Click <a href='./urlShortener.html'>here</a> to enter the URL again</p>";
            exit();
        }

        //Trim the URL before we add to the database

        //We will trim four cases from the string
        /*
            1:http://
            2:https://
            3:http://www.
            4:https://www.
        */

        $casesToTrim = array("http://","https://","http://www.","https://www.");
        $longURL =str_replace($casesToTrim,"",$longURL);
        /*//Commented out
        $longURL = str_replace("http://","",$longURL);
        $longURL = str_replace("https://","",$longURL);
        $longURL = str_replace("http://www.","",$longURL);
        $longURL = str_replace("https://www.","",$longURL);
        */
        //print $longURL;//For debugging purposes

        //Now, we should connect to the database
        //If it can't, the function will output the appropriate message
        $myConnection = connectToDatabase();
        print "<p>Successfully Connected to database.</p>";

        //Add the URL to the database
        addURL($myConnection,$longURL);

        //Close the connection
        $myConnection = null;
        ?>
    </body>
</html>