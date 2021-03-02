<?php
/*
Created by: Logan Crockett
On: 8/16/20
Purpose: To hold the functions used in urlShortener.php
*/

/**
 * This Function will connect to the shortURL database using PDO; It will return a connection to the database if successful, or an error message
 * @return connection connection to the MySQL database
 */
function connectToDatabase() {

    $dsn = "mysql:dbname=shorturl; host=localhost";
    $userName = "shortUrlAccess";
    $pw = "1234";

    //Try connecting with the given credentials
    try {
        $connection = new PDO($dsn, $userName, $pw);
    } catch (PDOException $e) {
        //If not successful, then output the error message
        //echo 'Connection failed: ' . $e->getMessage();
        print "<p>Unable to connect to the Database.</p>";
        exit();
    }

    //At this point, it will have been successful, and we can return the connection
    return $connection;
}

/**
 * This function will check the database and if the URL exists,
 * return the short URL; Otheriwse, return nothing.
 * @param connection Connection to a MySQL database
 * @param URL Url we are searching the DB for
 * @return shortURL the short URL from the database; Otherwise, it returns nothing
 */
function checkForShortURL ($connection,$longURL) {

    //Prepare a statement to execute
    $stmt = $connection->prepare("select shortURL from shorturl.url u where u.longURL = ?;");
    //Bind the parameter and execute
    $stmt->bindValue(1,$longURL,PDO::PARAM_STR);
    $stmt->execute();

    //Now fetch the results
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    //If it is empty, then print a message stating that no results were returned
    if (empty($result)) {
        //print "No results returned; No links match ".$longURL;//For debugging purposes
        return;
    }

    //Otherwise, return the short URL
    else {
        $shortURL = $result['shortURL'];
        //print $shortURL;//For debugging purposes
        return $shortURL;
    }
}

/**
 * This Function will check the DB for a long URL using the Short URL
 * @param connection Connection to a MySQL database
 * @param shortURL The Short URL we are chekcing
 * @return longURL The long URL from the DB; Otherwise, it returns nothing
 */
function checkForLongURL ($connection,$shortURL) {
    //Prepare a statement to execute
    $stmt = $connection->prepare("select longURL from shorturl.url u where u.shortURL = ?;");
    //Bind the parameter and execute
    $stmt->bindValue(1,$shortURL,PDO::PARAM_STR);
    $stmt->execute();

    //Now we fetch the results
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($result)) {
        //print "No results returned; No links match urlshortener.php".$shortURL;//For debugging purposes
        return;
    }
    //Otherwise, return the long URL
    else {
        $longURL = $result['longURL'];
        //print $longURL;//For debugging purposes
        return $longURL;
    }
}


/**
 * This function will generate a short URL and add it to the database before outputting to user
 * @param connection Connection to a MySQL database
 * @param URL Url we are searching the DB for
 */
function addURL($connection,$longURL) {

    //Start by checking if it exists
    //If true, then we can just fetch the value from the database and output it
    $shortURL = checkForShortURL($connection,$longURL);
    if (isset($shortURL)) {
        print "<p>The link has been generated previously. It can be accessed at: http://urlShortener.php/".$shortURL."</p>";
        return;
    }

    //Otherwise, we will create the short URL and add it to the database

    /*
        Create a short URL
        We will generate the URL based on the length of the original
    */

    $shortURL = "";//Start with a blank string

    /*//Commented out
    //Generate four letters
    for ($i = 0; $i < 4; $i++) {

        $randomLetter = rand(0,58) + 'a';//generate from [0,58] so we can get all letters from the ASCII Alphabet
        $shortURL += $randomLetter;//Add to the URL
    }
    */

    //Now add some numbers based on length
    /*
    Chart:
    25: Add 6 more values
    50: Add 12 more values
    >50: Add 18 more values
    */

    //Create a string of acceptable characters we can use
    $acceptableCharacters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    if (strlen($longURL) < 25) {
        for ($i = 0; $i < 6; $i++) {
            $shortURL .= $acceptableCharacters[rand(0,strlen($acceptableCharacters)-1)];
        }
    }

    else if (strlen($longURL) < 50){
        for ($i = 0; $i < 12; $i++) {
            $shortURL .= $acceptableCharacters[rand(0,strlen($acceptableCharacters)-1)];
        }
    }

    else {
        for ($i = 0; $i < 18; $i++) {
            $shortURL .= $acceptableCharacters[rand(0,strlen($acceptableCharacters)-1)];
        }
    }

    //print "Short URL: ".$shortURL;

    //Add the shortURL to the database

    //We will add the full links to the database, so rather than waste time adding the correct http:// info,
    //we can just go ahead and redirect the user to their page
    //For the Short URL's, we will just add the part after the final slash
    //Ex: urlshortner.php/abcdef will just store 'abcdef'

    
    //Prepare a statement to execute
    $stmt = $connection->prepare("insert into shorturl.url (longURL, shortURL) values (?,?);");
    //Bind the parameters and execute
    $stmt->bindValue(1,$longURL,PDO::PARAM_STR);
    $stmt->bindValue(2,$shortURL,PDO::PARAM_STR);
    $result = $stmt->execute();

    //Now check and see if it was successfully added
    //If true, then it was successful; Output a message
    if ($result) {
        print "<p>The link has been successfully added at: ";
        print "http://urlshortener.php/".$shortURL."</p>";
    }
    //Otherwise, print an error message
    else {
        print "<p>An error has occured. Unable to add entry to the database</p>";
    }
    
    return;
}

/*
Created by: Logan Crockett
On: 8/28/20
Purpose: To increment the number of redirects performed on a link
*/
/**
 * This function will increment the number of redirects performed on a link in the DB
 * @param connection Connection to a MySQL Database
 * @param shortURL The Short URL we are increment the redirects of
 * @return
 */
function incrementRedirects($connection,$shortURL) {
    //Prepare a statement to execute
    $stmt = $connection->prepare("update shorturl.url u set numRedirects = numRedirects + 1 where shortURL = ?;");
    //Bind the parameter and execute
    $stmt->bindValue(1,$shortURL,PDO::PARAM_STR);
    $result = $stmt->execute();//Will return a boolean value if it was successful

    //Check and see if it was successful

    if ($result) {//If true, then do nothing unless debugging
        print "<p>Successfully updated the number of redirects!</p>";//For debugging purposes
    }

    //We should not have to output an error message if it failed
    //THe URL will be checked to see if it exists before this function is called
    return;
}
?>