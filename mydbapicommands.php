
<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, x-Requested-with");
    header("Content-Type: application/json; charset=utf-8");

    include "library/config.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/phpmailer/phpmailer/src/Exception.php';
    require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require 'vendor/phpmailer/phpmailer/src/SMTP.php';

    require 'vendor/autoload.php';

    $postjson = json_decode(file_get_contents('php://input'), true);

/*****************************************************************SIGNUP 1 ********************************************/
     // if the function we are  calling from the project is 'displayallaccounts1' then peform the followin db commands
     if($postjson['mydbfunc'] == 'displayallaccounts1'){
        //create array to pick row values from database
        $data = array();

        $escapedmsgquotes1 = addslashes($postjson['usernameDB']);
        $escapedmsgquotes2 = addslashes($postjson['passwordDB']);

        //read from account table
        $query = mysqli_query($mysqli, "SELECT * FROM useraccount WHERE  username  = '$escapedmsgquotes1' OR userpassword = '$escapedmsgquotes2' ");

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'user_id_fetched' => $row['user_id'],
                'username_fetched' => $row['username'],
                'userpassword_fetched' => $row['userpassword'],
                'profile_pic_fetched' => $row['profile_pic'],
                'about_fetched' => $row['about'],
                'date_joined_fetched' => $row['date_joined']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }

/*****************************************************************SIGNUP 1 ********************************************/




/******************************************************************SIGNUP 2(with profile pic)*****************************************/
    //if the function we are  calling from the project is 'add_acount' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'addaccount_withprofilepic'){
        $data = array();

        $escapedmsgquotes1 =addslashes($postjson['usernameDB']);
        $escapedmsgquotes2 =addslashes($postjson['passwordDB']);
        $escapedmsgquotes3 =addslashes($postjson['aboutmeDB']);

        /*********************************************12 */
        //$imagesaveddate = date('Y-m-d'); //this generates at time and date and attaches it to the saved image's name to distinguish images
        $saveimagetime = date('H-i-s');  //this generates at time and attaches it to the saved image's name to distinguish images
        $entry = base64_decode($postjson['profilepicDB']);
        $img = imagecreatefromstring($entry);
        $directory = "userprofilepics/img_user".$saveimagetime.".jpg";  //save picture to folder server and name it as img_user.jpg inside /images folder in the camfilapi folder
        imagejpeg($img, $directory);
        imagedestroy($img);

        //passing values from variables in project to table columns
        //columns values on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  useraccount SET
        username   = '$escapedmsgquotes1',
        userpassword = '$escapedmsgquotes2',     
        profile_pic = '$directory', /******************13*/
        about ='$escapedmsgquotes3',
        date_joined =  '$postjson[datejoinedDB]'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $idadd = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'idadd' => $idadd));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************SIGNUP 2(with profile pic)**************************************************/






/******************************************************************SIGNUP 2(without profile pic)*****************************************/
    //if the function we are  calling from the project is 'add_acount' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'addaccount_withoutprofilepic'){
        $data = array();

        $escapedmsgquotes1 =addslashes($postjson['usernameDB']);
        $escapedmsgquotes2 =addslashes($postjson['passwordDB']);
        $escapedmsgquotes3 =addslashes($postjson['aboutmeDB']);

        //passing values from variables in project to table columns
        //columns values on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  useraccount SET
        username   = '$escapedmsgquotes1',
        userpassword = '$escapedmsgquotes2',     
        about ='$escapedmsgquotes3',
        date_joined =  '$postjson[datejoinedDB]'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $idadd = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'idadd' => $idadd));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************SIGNUP 2(without profile pic)**************************************************/








/***************************************************************LOGIN********************************************************* */
        // else if the function we are  calling from the project is 'displayallaccounts' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displayallaccounts'){
        //create array to pick row values from database
        $data = array();

        //escaping quotes to allow insert or read from database
        $escapedusernamequotes = addslashes($postjson['usernameDB']);
        $escapeduserpasswordquotes = addslashes($postjson['passwordDB']);


        //read from account table
        $query = mysqli_query($mysqli, "SELECT * FROM useraccount WHERE  username  = '$escapedusernamequotes' AND userpassword = '$escapeduserpasswordquotes' ");

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'user_id_fetched' => $row['user_id'],
                'username_fetched' => $row['username'],
                'userpassword_fetched' => $row['userpassword'],
                'profile_pic_fetched' => $row['profile_pic'],
                'about_fetched' => $row['about'],
                'date_joined_fetched' => $row['date_joined'],
                'date_leaving_fetched' => $row['date_leaving']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/***************************************************************LOGIN********************************************************* */










/**************************************************************READ ALL MESSAGES****************************************************/

    // else if the function we are  calling from the project is 'displayallmessages' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displayallmessages'){
        //create array to pick row values from database
        $data = array();

        //read all messages statement
        $query = mysqli_query($mysqli, "SELECT * FROM messages a JOIN useraccount b ON a.user_id = b.user_id ORDER BY message_id DESC"); //JOIN allows u to combine two tables (a and b) using the link (fk of a) = (pk of b)

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'message_id_fetched' => $row['message_id'],
                'message_fetched' => $row['message'],
                'message_media_fetched' => $row['message_media'],
                'likes_fetched' => $row['likes'],
                'comments_fetched' => $row['comments'],
                'date_created_fetched' => $row['date_created'],
                'user_id_fetched' => $row['user_id'],
                'username_fetched' => $row['username'],
                'sender_profile_pic_fetched' => $row['profile_pic']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************READ ALL MESSAGES****************************************************/



/**************************************************************READ USER MESSAGES****************************************************/

    // else if the function we are  calling from the project is 'displayallmessages' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displayspecificusermessages'){
        //create array to pick row values from database
        $data = array();

        //read user messages statement
        $query = mysqli_query($mysqli, "SELECT useraccount.user_id, useraccount.username, useraccount.profile_pic, messages.message, messages.message_id, messages.likes, messages.comments, messages.date_created, messages.message_media
        FROM messages
        JOIN useraccount
            ON messages.user_id = useraccount.user_id
        WHERE useraccount.user_id = '$postjson[globaluseridDB]' ORDER BY message_id DESC");

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'user_id_fetched' => $row['user_id'],
                'username_fetched' => $row['username'],
                'user_profile_pic_fetched' => $row['profile_pic'],
                'message_fetched' => $row['message'],
                'message_id_fetched' => $row['message_id'],
                'likes_fetched' => $row['likes'],
                'comments_fetched' => $row['comments'],
                'date_created_fetched' => $row['date_created'],
                'message_media_fetched' => $row['message_media'],
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************READ USER MESSAGES****************************************************/


/***************************************************************READ LOGGED IN OR SIGNED UP USER DETAILS********************************************************* */
        // else if the function we are  calling from the project is 'displayallaccounts' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'displayloggedinorsignedupuserdetails'){
            //create array to pick row values from database
            $data = array();
    
            //read from account table
            $query = mysqli_query($mysqli, "SELECT * FROM useraccount WHERE  user_id = '$postjson[globaluseridDB]' ");
    
            //php variables on the left..column names on the right
            //we created the php variables here and assigned row values from respective table columns into the variables
            while($row = mysqli_fetch_array($query)){
                $data[]= array(
                    'user_id_fetched' => $row['user_id'],
                    'username_fetched' => $row['username'],
                    'userpassword_fetched' => $row['userpassword'],
                    'profile_pic_fetched' => $row['profile_pic'],
                    'about_fetched' => $row['about'],
                    'date_joined_fetched' => $row['date_joined'],
                    'default_group_id_fetched' => $row['default_group_id'],
                    'user_email_fetched' => $row['user_email'],
                    'user_website_fetched' => $row['user_website'],
                    'user_phonenumber_fetched' => $row['user_phonenumber'],
                );
            }
    
            //if query executed put all fetched rows from $data array into result
            if($query) {
                $result = json_encode($data);   
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /***************************************************************READ LOGGED IN OR SIGNED UP USER DETAILS********************************************************* */


    /******************************************************************INSERT MESSAGE WITH MEDIA*****************************************/
    //if the function we are  calling from the project is 'add_acount' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'insertmsgwithmedia'){
        $data = array();

        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours

        $messagetime = date('H:i'); //generating our time for the message

        $escapedmsgquotes =addslashes($postjson[messageDB]);

        /*********************************************12 */
        //$imagesaveddate = date('Y-m-d'); //this generates a time and date and attaches it to the saved image's name to distinguish images
        $saveimagetime = date('H-i-s');  //this generates a time and attaches it to the saved image's name to distinguish images
        $entry = base64_decode($postjson['message_mediaDB']);
        $img = imagecreatefromstring($entry);
        $directory = "messagemedia/msg_media".$saveimagetime.".jpg";  //save picture to folder server and name it as img_user.jpg inside /images folder in the camfilapi folder
        imagejpeg($img, $directory);
        imagedestroy($img);

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  messages SET
        message   = '$escapedmsgquotes',
        message_media = '$directory', /******************13*/
        likes ='$postjson[likesDB]',
        comments =  '$postjson[commentsDB]',
        date_created =  '$messagetime',    /**php function date() automatically generates current date and time value**/
        user_id = '$postjson[user_idDB]'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $idadd = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'idadd' => $idadd));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************INSERT MESSAGE WITH MEDIA**************************************************/




/******************************************************************INSERT MESSAGE WITHOUT MEDIA*****************************************/
    //if the function we are  calling from the project is 'add_acount' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'insertmsgwithoutmedia'){
        $data = array();

        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours

        $messagetime = date('H:i'); //generating our time for the message
        
        $escapedmsgquotes =addslashes($postjson[messageDB]);


        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  messages (message, likes, comments, date_created, user_id) values
        ( '$escapedmsgquotes', '$postjson[likesDB]', '$postjson[commentsDB]', '$messagetime',  '$postjson[user_idDB]') ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $idadd = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'idadd' => $idadd));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************INSERT MESSAGE WITHOUT MEDIA**************************************************/




/***************************************************************DELETE MESSAGE********************************************************* */
        // else if the function we are  calling from the project is 'deletemessage' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'deletemessage'){
            
            //delete from messages table
            $query = mysqli_query($mysqli, "DELETE FROM messages WHERE  message_id = '$postjson[messageidDB]' ");
    
            //if query executed show success message
            if($query) {
                $result = json_encode(array('success'=>true)); 
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /***************************************************************DELETE MESSAGE********************************************************* */




    /**************************************************************READ USER PINNED MESSAGES****************************************************/

    // else if the function we are  calling from the project is 'displayallmessages' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displayuserpinnedmessages'){
        //create array to pick row values from database
        $data = array();

        //read all messages statement
        $query = mysqli_query($mysqli, "SELECT messages.message_id, messages.message,messages.message_media, messages.likes, messages.comments, messages.date_created, messages.user_id ,useraccount.username, useraccount.profile_pic
        FROM pinnedmessages
        
        JOIN messages
            ON pinnedmessages.msg_id = messages.message_id
				
        JOIN useraccount 
        	ON messages.user_id = useraccount.user_id
            
        WHERE pinnedmessages.user_id = '$postjson[globaluseridDB]' ORDER BY message_id DESC"); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'message_id_fetched' => $row['message_id'],
                'message_fetched' => $row['message'],
                'message_media_fetched' => $row['message_media'],
                'likes_fetched' => $row['likes'],
                'comments_fetched' => $row['comments'],
                'date_created_fetched' => $row['date_created'],
                'msgsender_id_fetched' => $row['user_id'],
                'msgsender_username_fetched' => $row['username'],
                'msgsender_profile_pic_fetched' => $row['profile_pic']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************READ USER PINNED MESSAGES****************************************************/



/******************************************************************PIN MESSAGE*****************************************/
    //if the function we are  calling from the project is 'pinmessage' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'pinmessage'){
        $data = array();

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  pinnedmessages SET
        user_id   = '$postjson[pinneruseridDB]',
        msg_id ='$postjson[pinnedmessageidDB]'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $idadd = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'idadd' => $idadd));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************PIN MESSAGE**************************************************/





/***************************************************************UNPIN MESSAGE********************************************************* */
        // else if the function we are  calling from the project is 'deletemessage' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'unpinmessage'){
            
            //delete from pinnedmessages table
            $query = mysqli_query($mysqli, "DELETE FROM pinnedmessages WHERE  user_id = '$postjson[pinneruseridDB]' AND msg_id = '$postjson[pinnedmessageidDB]' ");
    
            //if query executed show success message
            if($query) {
                $result = json_encode(array('success'=>true)); 
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /***************************************************************UNPIN MESSAGE********************************************************* */




/**************************************************************DISPLAY ALL USERS****************************************************/

    // else if the function we are  calling from the project is 'displayallusers' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displayallusers'){
        //create array to pick row values from database
        $data = array();

        //display all users statement
        $query = mysqli_query($mysqli, "SELECT * FROM useraccount");

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'user_id_fetched' => $row['user_id'],
                'username_fetched' => $row['username'],
                'userpassword_fetched' => $row['userpassword'],
                'user_profile_pic_fetched' => $row['profile_pic'],
                'user_about_fetched' => $row['about'],
                'user_date_joined_fetched' => $row['date_joined']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************DISPLAY ALL USERS****************************************************/
    



/******************************************************************FOLLOW USER*****************************************/
    //if the function we are  calling from the project is 'followuser' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'followuser'){
        $data = array();

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  friendship SET
        follower_id   = '$postjson[logged_in_useridDB]',
        followed_id ='$postjson[followed_useridDB]'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $idadd = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'idadd' => $idadd));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/************************************************************FOLLOW USER**************************************************/




/***************************************************************UNFOLLOW USER********************************************************* */
        // else if the function we are  calling from the project is 'unfollowuser' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'unfollowuser'){
            
            //delete from pinnedmessages table
            $query = mysqli_query($mysqli, "DELETE FROM friendship WHERE  follower_id   = '$postjson[logged_in_useridDB]'  AND followed_id ='$postjson[followed_useridDB]' ");
    
            //if query executed show success message
            if($query) {
                $result = json_encode(array('success'=>true)); 
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /***************************************************************UNFOLLOW USER********************************************************* */






    /**************************************************************LOAD USER'S FOLLOWERS****************************************************/

    // else if the function we are  calling from the project is 'loaduserfollowers' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'loaduserfollowers'){
        //create array to pick row values from database
        $data = array();

        //read all user followers statement
        $query = mysqli_query($mysqli, "SELECT useraccount.user_id, useraccount.username, useraccount.userpassword, useraccount.profile_pic, useraccount.about, useraccount.date_joined
        FROM friendship
        
        JOIN useraccount
            ON friendship.follower_id = useraccount.user_id     
            
        WHERE friendship.followed_id = '$postjson[globaluseridDB]' "); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'follower_userid_fetched' => $row['user_id'],
                'follower_username_fetched' => $row['username'],
                'follower_userpassword_fetched' => $row['userpassword'],
                'follower_profile_pic_fetched' => $row['profile_pic'],
                'follower_about_fetched' => $row['about'],
                'follower_date_joined_fetched' => $row['date_joined']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************LOAD USER'S FOLLOWERS****************************************************/





 /**************************************************************LOAD USER'S FOLLOWING****************************************************/

    // else if the function we are  calling from the project is 'loaduserfollowers' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'loaduserfollowing'){
        //create array to pick row values from database
        $data = array();

        //read all user followers statement
        $query = mysqli_query($mysqli, "SELECT useraccount.user_id, useraccount.username, useraccount.userpassword, useraccount.profile_pic, useraccount.about, useraccount.date_joined
        FROM friendship
        
        JOIN useraccount
            ON friendship.followed_id = useraccount.user_id     
            
        WHERE friendship.follower_id = '$postjson[globaluseridDB]' "); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'following_userid_fetched' => $row['user_id'],
                'following_username_fetched' => $row['username'],
                'following_userpassword_fetched' => $row['userpassword'],
                'following_profile_pic_fetched' => $row['profile_pic'],
                'following_about_fetched' => $row['about'],
                'following_date_joined_fetched' => $row['date_joined']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************LOAD USER'S FOLLOWING****************************************************/




 /**************************************************************RETRIEVE NUMBER OF USERS LOGGED IN USER IS FOLLOWING****************************************************/

    // else if the function we are  calling from the project is 'countfollowing' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'countfollowing'){
        //create array to pick row values from database
        $data = array();

        //read all user followers statement
        $query = mysqli_query($mysqli, "SELECT COUNT(*) FROM friendship WHERE follower_id = '$postjson[globaluseridDB]' "); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'following_rows_fetched' => $row['COUNT(*)']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************RETRIEVE NUMBER OF USERS LOGGED IN USER IS FOLLOWING****************************************************/





/**************************************************************RETRIEVE NUMBER OF FOLLOWERS OF LOGGED IN USER****************************************************/

    // else if the function we are  calling from the project is 'countfollowers' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'countfollowers'){
        //create array to pick row values from database
        $data = array();

        //read all user followers statement
        $query = mysqli_query($mysqli, "SELECT COUNT(*) FROM friendship WHERE followed_id = '$postjson[globaluseridDB]' "); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'followers_rows_fetched' => $row['COUNT(*)']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************RETRIEVE NUMBER OF FOLLOWERS OF LOGGED IN USER****************************************************/





/**************************************************************RETRIEVE NUMBER OF COMMENTS OF LOGGED IN USER****************************************************/

    // else if the function we are  calling from the project is 'countcomments' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'countcomments'){
        //create array to pick row values from database
        $data = array();

        //read all user followers statement
        $query = mysqli_query($mysqli, "SELECT COUNT(*) FROM commentedmessages WHERE user_id = '$postjson[globaluseridDB]' "); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'comments_rows_fetched' => $row['COUNT(*)']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************RETRIEVE NUMBER OF COMMENTS OF LOGGED IN USER****************************************************/






/**************************************************************RETRIEVE NUMBER OF LIKES OF LOGGED IN USER****************************************************/

    // else if the function we are  calling from the project is 'countlikes' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'countlikes'){
        //create array to pick row values from database
        $data = array();

        //read all user followers statement
        $query = mysqli_query($mysqli, "SELECT COUNT(*) FROM likedmessages WHERE user_id = '$postjson[globaluseridDB]' "); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'likes_rows_fetched' => $row['COUNT(*)']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************RETRIEVE NUMBER OF LIKES OF LOGGED IN USER****************************************************/




/**************************************************************RETRIEVE NUMBER OF POSTED MESSAGES OF LOGGED IN USER****************************************************/

    // else if the function we are  calling from the project is 'countusermessages' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'countusermessages'){
        //create array to pick row values from database
        $data = array();

        //read all user followers statement
        $query = mysqli_query($mysqli, "SELECT COUNT(*) FROM messages WHERE user_id = '$postjson[globaluseridDB]' "); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'usermessages_rows_fetched' => $row['COUNT(*)']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************RETRIEVE NUMBER OF POSTED MESSAGES OF LOGGED IN USER****************************************************/




/******************************************************************LIKE MESSAGE*****************************************/
    //if the function we are  calling from the project is 'likemessage' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'likemessage'){
        $data = array();

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  likedmessages SET
        user_id   = '$postjson[likeruseridDB]',
        likedmsg_id ='$postjson[likedmessageidDB]'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $idadd = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'idadd' => $idadd));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************LIKE MESSAGE**************************************************/





/***************************************************************UNLIKE MESSAGE********************************************************* */
        // else if the function we are  calling from the project is 'unlikemessage' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'unlikemessage'){
            
            //delete from pinnedmessages table
            $query = mysqli_query($mysqli, "DELETE FROM likedmessages WHERE  user_id = '$postjson[likeruseridDB]' AND likedmsg_id = '$postjson[likedmessageidDB]' ");
    
            //if query executed show success message
            if($query) {
                $result = json_encode(array('success'=>true)); 
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /***************************************************************UNLIKE MESSAGE********************************************************* */



/******************************************************************UPDATE TOTAL MESSAGE LIKES*****************************************/
    //if the function we are  calling from the project is 'updatetotallikes' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'updatetotallikes'){
        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "UPDATE messages SET
        likes   = '$postjson[messagelikesDB]' WHERE message_id = '$postjson[messageidDB]'
        ");

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************UPDATE TOTAL MESSAGE LIKES**************************************************/







 /**************************************************************READ LOGGED IN USER LIKED MESSAGES****************************************************/

    // else if the function we are  calling from the project is 'displayuserlikedmessages' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displayuserlikedmessages'){
        //create array to pick row values from database
        $data = array();

        //read all messages statement
        $query = mysqli_query($mysqli, "SELECT messages.message_id, messages.message,messages.message_media, messages.likes, messages.comments, messages.date_created, messages.user_id ,useraccount.username, useraccount.profile_pic
        FROM likedmessages
        
        JOIN messages
            ON likedmessages.likedmsg_id = messages.message_id
				
        JOIN useraccount 
        	ON messages.user_id = useraccount.user_id
            
        WHERE likedmessages.user_id = '$postjson[globaluseridDB]' ORDER BY message_id DESC"); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'message_id_fetched' => $row['message_id'],
                'message_fetched' => $row['message'],
                'message_media_fetched' => $row['message_media'],
                'likes_fetched' => $row['likes'],
                'comments_fetched' => $row['comments'],
                'date_created_fetched' => $row['date_created'],
                'msgsender_id_fetched' => $row['user_id'],
                'msgsender_username_fetched' => $row['username'],
                'msgsender_profile_pic_fetched' => $row['profile_pic']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************READ LOGGED IN USER LIKED MESSAGES****************************************************/



/**************************************************************READ COMMENTED MESSAGE****************************************************/

    // else if the function we are  calling from the project is 'displaycommentedmessage' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displaycommentedmessage'){
        //create array to pick row values from database                         
        $data = array();

        //read all messages statement
        $query = mysqli_query($mysqli, "SELECT * FROM messages a JOIN useraccount b ON a.user_id = b.user_id WHERE message_id= '$postjson[commentedmessageidDB]'
        "); //JOIN allows u to combine two tables (a and b) using the link (fk of a) = (pk of b)

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'commented_message_id_fetched' => $row['message_id'],
                'commented_message_fetched' => $row['message'],
                'commented_message_media_fetched' => $row['message_media'],
                'commentedmsg_likes_fetched' => $row['likes'],
                'commentedmsg_comments_fetched' => $row['comments'],
                'commentedmsg_date_created_fetched' => $row['date_created'],
                'commentedmsg_sender_user_id_fetched' => $row['user_id'],
                'commentedmsg_sender_username_fetched' => $row['username'],
                'commentedmsg_sender_profile_pic_fetched' => $row['profile_pic']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************READ COMMENTED MESSAGE****************************************************/





/******************************************************************COMMENT MESSAGE WITH MEDIA*****************************************/
    //if the function we are  calling from the project is 'add_acount' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'commentmsgwithmedia'){
        $data = array();

        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours

        $messagetime = date('H:i'); //generating our time for the message

        $escapedmsgquotes =addslashes($postjson[messageDB]);

        /*********************************************12 */
        //$imagesaveddate = date('Y-m-d'); //this generates a time and date and attaches it to the saved image's name to distinguish images
        $saveimagetime = date('H-i-s');  //this generates a time and attaches it to the saved image's name to distinguish images
        $entry = base64_decode($postjson['message_mediaDB']);
        $img = imagecreatefromstring($entry);
        $directory = "messagemedia/msg_media".$saveimagetime.".jpg";  //save picture to folder server and name it as img_user.jpg inside /images folder in the camfilapi folder
        imagejpeg($img, $directory);
        imagedestroy($img);

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  messages SET
        message   = '$escapedmsgquotes',
        message_media = '$directory', /******************13*/
        likes ='$postjson[likesDB]',
        comments =  '$postjson[commentsDB]',
        date_created =  '$messagetime',    /**php function date() automatically generates current date and time value**/
        user_id = '$postjson[user_idDB]'
        ");

        //fetch the id or PK of the row inserted and assign it to $commentmsgid 
        //this retrieves the msg id of the inserted message
        $commentmsgid = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'commentmsgid' => $commentmsgid));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;

        //now we have posted our message into the messagetable..next we add 1 to the comments of the commentedmessage
        $query = mysqli_query($mysqli, "UPDATE messages SET
        comments   = '$postjson[commentedmsgtotalcommentsDB]' WHERE message_id = '$postjson[commentedmsg_idDB]'
        ");

        //now we have posted our message into the messagetable..next we insert the required info into the commentedmessages table
        $query = mysqli_query($mysqli, "INSERT INTO  commentedmessages SET
        user_id   = '$postjson[user_idDB]', /*id of user commenting the message*/
        commentmsg_id = $commentmsgid,                /*id of the comment msg: we retrieved it from the first query*/
        commentedmsg_id = '$postjson[commentedmsg_idDB]'    /*id of the commented message*/
        ");

        //finally we insert our details into the notification table to inform commented msg user that his messages has been commented
        $query = mysqli_query($mysqli, "INSERT INTO  notifications SET
        notmsg_type   = 'comment',
        sender_id ='$postjson[user_idDB]',
        sender_msg_id = $commentmsgid,                      /*id of the comment msg: we retrieved it from the first query*/
        receiver_id   = '$postjson[receiveridDB]',
        receiver_msg_id   = '$postjson[commentedmsg_idDB]',
        date_created =  '$messagetime',    /**php function date() automatically generates current date and time value**/
        read_status   = 'unread'
        ");
    }
/*************************************************************COMMENT MESSAGE WITH MEDIA**************************************************/




/******************************************************************COMMENT MESSAGE WITHOUT MEDIA*****************************************/
    //if the function we are  calling from the project is 'add_acount' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'commentmsgwithoutmedia'){
        $data = array();

        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours

        $messagetime = date('H:i'); //generating our time for the message

        $escapedmsgquotes =addslashes($postjson[messageDB]);

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  messages SET
        message   = '$escapedmsgquotes',
        likes ='$postjson[likesDB]',
        comments =  '$postjson[commentsDB]',
        date_created =  '$messagetime',    /**php function date() automatically generates current date and time value**/
        user_id = '$postjson[user_idDB]'
        ");

        //fetch the id or PK of the row inserted and assign it to $commentmsgid 
        //this retrieves the msg id of the inserted message
        $commentmsgid = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'commentmsgid' => $commentmsgid));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;

        //now we have posted our message into the messagetable..next we add 1 to the comments of the commentedmessage
        $query = mysqli_query($mysqli, "UPDATE messages SET
        comments   = '$postjson[commentedmsgtotalcommentsDB]' WHERE message_id = '$postjson[commentedmsg_idDB]'
        ");

        //now we have posted our message into the messagetable..next we insert the required info into the commentedmessages table
        $query = mysqli_query($mysqli, "INSERT INTO  commentedmessages SET
        user_id   = '$postjson[user_idDB]',         /*id of user commenting the message*/
        commentmsg_id = $commentmsgid,                      /*id of the comment msg: we retrieved it from the first query*/
        commentedmsg_id = '$postjson[commentedmsg_idDB]'        /*id of the commented message*/
        ");


        //finally we insert our details into the notification table to inform commented msg user that his messages has been commented
        $query = mysqli_query($mysqli, "INSERT INTO  notifications SET
        notmsg_type   = 'comment',
        sender_id ='$postjson[user_idDB]',
        sender_msg_id = $commentmsgid,                      /*id of the comment msg: we retrieved it from the first query*/
        receiver_id   = '$postjson[receiveridDB]',
        receiver_msg_id   = '$postjson[commentedmsg_idDB]',
        date_created =  '$messagetime',    /**php function date() automatically generates current date and time value**/
        read_status   = 'unread'
        ");

    }
/*************************************************************COMMENT MESSAGE WITHOUT MEDIA**************************************************/








/***************************READ LOGGED IN ALL COMMENT MSG IDS AND ALL INFO OF THE RESPECTIVE MESSAGES THEY COMMENTED********************************* */
/**we dont read for a specific logged in user this time because we want to see all commented messages not just the one the user commented but everyone else in the app */

 // else if the function we are  calling from the project is 'displayallmessages' then peform the followin db commands
 elseif($postjson['mydbfunc'] == 'displayusercommentmsgids_and_commentedmsgs_info'){
    //create array to pick row values from database
    $data = array();

    //read all messages statement
    $query = mysqli_query($mysqli, "SELECT commentedmessages.user_id AS commenter_userid, commentedmessages.commentmsg_id AS commenter_msg_id , messages.message_id AS commentedmsg_id, messages.message AS commentedmsg_message ,messages.message_media AS commentedmsg_messagemedia, messages.likes AS commentedmsg_likes, messages.comments AS commentedmsg_comments, messages.date_created AS commentedmsg_date_created, messages.user_id AS commentedmsg_userid, useraccount.username AS commentedmsg_username, useraccount.profile_pic AS commentedmsg_profile_pic FROM commentedmessages 
	JOIN messages ON 
    	commentedmessages.commentedmsg_id = messages.message_id 
    JOIN useraccount ON 
    	messages.user_id = useraccount.user_id 
          
    ORDER BY message_id DESC"); 

    //php variables on the left..column names on the right
    //we created the php variables here and assigned row values from respective table columns into the variables
    while($row = mysqli_fetch_array($query)){
        $data[]= array(
            'commenter_userid_fetched' => $row['commenter_userid'],
            'commenter_msg_id_fetched' => $row['commenter_msg_id'],
            'commentedmsg_id_fetched' => $row['commentedmsg_id'],
            'commentedmsg_message_fetched' => $row['commentedmsg_message'],
            'commentedmsg_messagemedia_fetched' => $row['commentedmsg_messagemedia'],
            'commentedmsg_likes_fetched' => $row['commentedmsg_likes'],
            'commentedmsg_comments_fetched' => $row['commentedmsg_comments'],
            'commentedmsg_date_created_fetched' => $row['commentedmsg_date_created'],
            'commentedmsg_userid_fetched' => $row['commentedmsg_userid'],
            'commentedmsg_username_fetched' => $row['commentedmsg_username'],
            'commentedmsg_profile_pic_fetched' => $row['commentedmsg_profile_pic']
        );
    }

    //if query executed put all fetched rows from $data array into result
    if($query) {
        $result = json_encode($data);   
    }
    //else if query didnt execute
    else {
        $result = json_encode(array('success'=>false)); 
    }

    //finally output whatever was saved into result
    echo $result;
}

/***************************READ LOGGED IN USER'S COMMENT MSG IDS AND ALL INFO OF THE RESPECTIVE MESSAGES THEY COMMENTED********************************* */









/**********************************************************READ ALL COMMENTS OF A SPECIFIC MESSAGE*********************************************************** */

 // else if the function we are  calling from the project is 'loadspecificmessagecomments' then peform the followin db commands
 elseif($postjson['mydbfunc'] == 'loadspecificmessagecomments'){
    //create array to pick row values from database
    $data = array();

    //read all messages statement
    $query = mysqli_query($mysqli, "SELECT * FROM commentedmessages 
	JOIN messages ON commentedmessages.commentmsg_id = messages.message_id 
    
    JOIN useraccount ON messages.user_id = useraccount.user_id 
    
    WHERE commentedmsg_id= '$postjson[commentedmessageidDB]'
    
     ORDER BY message_id DESC"); 

    //php variables on the left..column names on the right
    //we created the php variables here and assigned row values from respective table columns into the variables
    while($row = mysqli_fetch_array($query)){
        $data[]= array(
            'commentedmsg_id_fetched' => $row['commentedmsg_id'],
            'commentmsg_id_fetched' => $row['commentmsg_id'],
            'commentmsg_message_fetched' => $row['message'],
            'commentmsg_messagemedia_fetched' => $row['message_media'],
            'commentmsg_likes_fetched' => $row['likes'],
            'commentmsg_comments_fetched' => $row['comments'],
            'commentmsg_date_created_fetched' => $row['date_created'],
            'commenter_userid_fetched' => $row['user_id'],
            'commenter_username_fetched' => $row['username'],
            'commenter_profile_pic_fetched' => $row['profile_pic']
        );
    }

    //if query executed put all fetched rows from $data array into result
    if($query) {
        $result = json_encode($data);   
    }
    //else if query didnt execute
    else {
        $result = json_encode(array('success'=>false)); 
    }

    //finally output whatever was saved into result
    echo $result;
}

/**********************************************************READ ALL COMMENTS OF A SPECIFIC MESSAGE*********************************************************** */





/**********************************************************READ SPECIFIC USER NOTIFICATIONS MESSAGES*********************************************************** */

 // else if the function we are  calling from the project is 'displayusernotificationmessages' then peform the followin db commands
 elseif($postjson['mydbfunc'] == 'displayusernotificationmessages'){
    //create array to pick row values from database
    $data = array();

    //read all user notifications messages statement
    $query = mysqli_query($mysqli, "SELECT notifications.notmsg_id, notifications.notmsg_type, notifications.read_status, notifications.date_created,

	a.user_id AS senderID , a.username AS senderUsername, a.profile_pic AS senderProfilepic, 
    
    c.message_id AS senderMessageID, c.message AS senderMessage, c.message_media AS senderMessagemedia, c.likes AS senderMessagelikes, c.comments AS senderMessagecomments, c.date_created AS senderMessagedate,

	b.user_id AS receiverID, b.username AS receiverUsername, b.profile_pic AS receiverProfilepic,
    
    d.message_id AS receiverMessageID, d.message AS receiverMessage, d.message_media AS receiverMessagemedia, d.likes AS 	receiverMessagelikes, d.comments AS receiverMessagecomments, d.date_created AS receiverMessagedate
    
    FROM notifications 

    JOIN useraccount a ON a.user_id = notifications.sender_id

    JOIN useraccount b ON b.user_id = notifications.receiver_id

    LEFT OUTER JOIN messages c on c.message_id = notifications.sender_msg_id

    LEFT OUTER JOIN messages d on d.message_id = notifications.receiver_msg_id

    WHERE receiver_id = '$postjson[globaluseridDB]'

    ORDER BY notmsg_id DESC"); 

    //php variables on the left..column names on the right
    //we created the php variables here and assigned row values from respective table columns into the variables
    while($row = mysqli_fetch_array($query)){
        $data[]= array(
            'notmsg_id_fetched' => $row['notmsg_id'],
            'notmsg_type_fetched' => $row['notmsg_type'],
            'read_status_fetched' => $row['read_status'],
            'date_created_fetched' => $row['date_created'],
            'senderID_fetched' => $row['senderID'],
            'senderUsername_fetched' => $row['senderUsername'],
            'senderProfilepic_fetched' => $row['senderProfilepic'],
            'senderMessageID_fetched' => $row['senderMessageID'],
            'senderMessage_fetched' => $row['senderMessage'],
            'senderMessagemedia_fetched' => $row['senderMessagemedia'],
            'senderMessagelikes_fetched' => $row['senderMessagelikes'],
            'senderMessagecomments_fetched' => $row['senderMessagecomments'],
            'senderMessagedate_fetched' => $row['senderMessagedate'],
            'receiverID_fetched' => $row['receiverID'],
            'receiverUsername_fetched' => $row['receiverUsername'],
            'receiverProfilepic_fetched' => $row['receiverProfilepic'],
            'receiverMessageID_fetched' => $row['receiverMessageID'],
            'receiverMessage_fetched' => $row['receiverMessage'],
            'receiverMessagemedia_fetched' => $row['receiverMessagemedia'],
            'receiverMessagelikes_fetched' => $row['receiverMessagelikes'],
            'receiverMessagecomments_fetched' => $row['receiverMessagecomments'],
            'receiverMessagedate_fetched' => $row['receiverMessagedate']
        );
    }

    //if query executed put all fetched rows from $data array into result
    if($query) {
        $result = json_encode($data);   
    }
    //else if query didnt execute
    else {
        $result = json_encode(array('success'=>false)); 
    }

    //finally output whatever was saved into result
    echo $result;
}

/**********************************************************READ SPECIFIC USER NOTIFICATION MESSSAGES*********************************************************** */



/******************************************************************SEND FOLLOW NOTIFICATION**************************************************************************/
    //if the function we are  calling from the project is 'sendfollownotification' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'sendfollownotification'){
        $data = array();

        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours

        $messagetime = date('H:i'); //generating our time for the message

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  notifications SET
        notmsg_type   = 'follow',
        sender_id ='$postjson[senderidDB]',
        receiver_id   = '$postjson[receiveridDB]',
        date_created =  '$messagetime',    /**php function date() automatically generates current date and time value**/
        read_status   = 'unread'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $idadd = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'idadd' => $idadd));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************SEND FOLLOW NOTIFICATION*******************************************************************************/



/***************************************************************REMOVE FOLLOW NOTIFICATION********************************************************* */
        // else if the function we are  calling from the project is 'deletfollownotification' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'deletefollownotification'){
            
            //delete from pinnedmessages table
            $query = mysqli_query($mysqli, "DELETE FROM notifications WHERE  notmsg_type = 'follow' AND sender_id = '$postjson[senderidDB]'  AND receiver_id = '$postjson[receiveridDB]' ");
    
            //if query executed show success message
            if($query) {
                $result = json_encode(array('success'=>true)); 
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /**********************************************************REMOVE FOLLOW NOTIFICATION********************************************************* */






/******************************************************************SEND LIKE NOTIFICATION**************************************************************************/
    //if the function we are  calling from the project is 'sendlikenotification' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'sendlikenotification'){
        $data = array();

        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours

        $messagetime = date('H:i'); //generating our time for the message

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  notifications SET
        notmsg_type   = 'like',
        sender_id ='$postjson[senderidDB]',
        receiver_id   = '$postjson[receiveridDB]',
        receiver_msg_id   = '$postjson[receivermsgidDB]',
        date_created =  '$messagetime',    /**php function date() automatically generates current date and time value**/
        read_status   = 'unread'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $idadd = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'idadd' => $idadd));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************SEND LIKE NOTIFICATION*******************************************************************************/






/***************************************************************REMOVE LIKE NOTIFICATION********************************************************* */
        // else if the function we are  calling from the project is 'deletelikenotification' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'deletelikenotification'){
            
            //delete from pinnedmessages table
            $query = mysqli_query($mysqli, "DELETE FROM notifications WHERE  notmsg_type = 'like' AND sender_id = '$postjson[senderidDB]'  AND receiver_id = '$postjson[receiveridDB]' AND receiver_msg_id = '$postjson[receivermsgidDB]' ");
    
            //if query executed show success message
            if($query) {
                $result = json_encode(array('success'=>true)); 
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /**********************************************************REMOVE LIKE NOTIFICATION********************************************************* */




    /******************************************************************UPDATE FOLLOW NOTIFICATION READ STATUS*****************************************/
    //if the function we are  calling from the project is 'updatefollowreadstatus' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'updatefollowreadstatus'){
        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "UPDATE notifications SET
        read_status   = 'read' 
        WHERE notmsg_type = 'follow' AND 
        sender_id = '$postjson[senderidDB]' AND
        receiver_id = '$postjson[receiveridDB]'
        ");

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************UPDATE FOLLOW NOTIFICATION READ STATUS**************************************************/



/******************************************************************UPDATE LIKE NOTIFICATION READ STATUS*****************************************/
    //if the function we are  calling from the project is 'updatelikereadstatus' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'updatelikereadstatus'){
        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "UPDATE notifications SET
        read_status   = 'read' 
        WHERE notmsg_type = 'like' AND 
        sender_id = '$postjson[senderidDB]' AND
        receiver_id = '$postjson[receiveridDB]' AND 
        receiver_msg_id ='$postjson[receivermsgidDB]'
        ");

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************UPDATE LIKE NOTIFICATION READ STATUS**************************************************/





/******************************************************************UPDATE COMMENT NOTIFICATION READ STATUS*****************************************/
    //if the function we are  calling from the project is 'updatecommentreadstatus' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'updatecommentreadstatus'){
        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "UPDATE notifications SET
        read_status   = 'read' 
        WHERE notmsg_type = 'comment' AND 
        sender_id = '$postjson[senderidDB]' AND
        sender_msg_id =  '$postjson[sendermsgidDB]' AND 
        receiver_id = '$postjson[receiveridDB]' AND 
        receiver_msg_id ='$postjson[receivermsgidDB]'
        ");

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************UPDATE COMMENT NOTIFICATION READ STATUS**************************************************/







/**********************************************************READ LOGGED IN USER UNREAD NOTIFICATIONS MESSAGES*********************************************************** */

 // else if the function we are  calling from the project is 'displayusernotificationmessages' then peform the followin db commands
 elseif($postjson['mydbfunc'] == 'displayunreadusernotificationmessages'){
    //create array to pick row values from database
    $data = array();

    //read all user notifications messages statement
    $query = mysqli_query($mysqli, "SELECT notifications.notmsg_id, notifications.notmsg_type, notifications.read_status, notifications.date_created,

	a.user_id AS senderID , a.username AS senderUsername, a.profile_pic AS senderProfilepic, 
    
    c.message_id AS senderMessageID, c.message AS senderMessage, c.message_media AS senderMessagemedia, c.likes AS senderMessagelikes, c.comments AS senderMessagecomments, c.date_created AS senderMessagedate,

	b.user_id AS receiverID, b.username AS receiverUsername, b.profile_pic AS receiverProfilepic,
    
    d.message_id AS receiverMessageID, d.message AS receiverMessage, d.message_media AS receiverMessagemedia, d.likes AS 	receiverMessagelikes, d.comments AS receiverMessagecomments, d.date_created AS receiverMessagedate
    
    FROM notifications 

    JOIN useraccount a ON a.user_id = notifications.sender_id

    JOIN useraccount b ON b.user_id = notifications.receiver_id

    LEFT OUTER JOIN messages c on c.message_id = notifications.sender_msg_id

    LEFT OUTER JOIN messages d on d.message_id = notifications.receiver_msg_id

    WHERE receiver_id = '$postjson[globaluseridDB]' AND read_status = 'unread'

    ORDER BY notmsg_id DESC"); 

    //php variables on the left..column names on the right
    //we created the php variables here and assigned row values from respective table columns into the variables
    while($row = mysqli_fetch_array($query)){
        $data[]= array(
            'notmsg_id_fetched' => $row['notmsg_id'],
            'notmsg_type_fetched' => $row['notmsg_type'],
            'read_status_fetched' => $row['read_status'],
            'date_created_fetched' => $row['date_created'],
            'senderID_fetched' => $row['senderID'],
            'senderUsername_fetched' => $row['senderUsername'],
            'senderProfilepic_fetched' => $row['senderProfilepic'],
            'senderMessageID_fetched' => $row['senderMessageID'],
            'senderMessage_fetched' => $row['senderMessage'],
            'senderMessagemedia_fetched' => $row['senderMessagemedia'],
            'senderMessagelikes_fetched' => $row['senderMessagelikes'],
            'senderMessagecomments_fetched' => $row['senderMessagecomments'],
            'senderMessagedate_fetched' => $row['senderMessagedate'],
            'receiverID_fetched' => $row['receiverID'],
            'receiverUsername_fetched' => $row['receiverUsername'],
            'receiverProfilepic_fetched' => $row['receiverProfilepic'],
            'receiverMessageID_fetched' => $row['receiverMessageID'],
            'receiverMessage_fetched' => $row['receiverMessage'],
            'receiverMessagemedia_fetched' => $row['receiverMessagemedia'],
            'receiverMessagelikes_fetched' => $row['receiverMessagelikes'],
            'receiverMessagecomments_fetched' => $row['receiverMessagecomments'],
            'receiverMessagedate_fetched' => $row['receiverMessagedate']
        );
    }

    //if query executed put all fetched rows from $data array into result
    if($query) {
        $result = json_encode($data);   
    }
    //else if query didnt execute
    else {
        $result = json_encode(array('success'=>false)); 
    }

    //finally output whatever was saved into result
    echo $result;
}

/**********************************************************READ LOGGED IN USER UNREAD NOTIFICATION MESSSAGES*********************************************************** */


/***************************************************************DELETE/ UPDATE PROFILE PIC********************************************************* */
    // else if the function we are  calling from the project is 'deleteprofilepic' then peform the followin db commands    
    elseif($postjson['mydbfunc'] == 'deleteprofilepic'){
        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "UPDATE useraccount SET
        profile_pic   = NULL  WHERE user_id = '$postjson[user_idDB]'
        ");

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/***************************************************************DELETE/ UPDATE PROFILE PIC********************************************************* */



/*****************************************************************VERIFY IF USERNAME EXISTS BEFORE UPDATE OR CHANGING IT********************************************/
     // if the function we are  calling from the project is 'searchusername' then peform the followin db commands
     elseif($postjson['mydbfunc'] == 'searchusername'){
        //create array to pick row values from database
        $data = array();

        $escapedmsgquotes1 = addslashes($postjson['usernameDB']);

        //read from account table
        $query = mysqli_query($mysqli, "SELECT * FROM useraccount WHERE  username  = '$escapedmsgquotes1' ");

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'user_id_fetched' => $row['user_id'],
                'username_fetched' => $row['username'],
                'userpassword_fetched' => $row['userpassword'],
                'profile_pic_fetched' => $row['profile_pic'],
                'about_fetched' => $row['about'],
                'date_joined_fetched' => $row['date_joined']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }

/*****************************************************************VERIFY IF USERNAME EXISTS BEFORE UPDATE OR CHANGING IT********************************************/



 /******************************************************************UPDATE PROFILE DETAILS WITHOUT PROFILE PIC*****************************************/
    //if the function we are  calling from the project is 'updateprofilewithoutprofilepic' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'updateprofilewithoutprofilepic'){
        $escapedusername =addslashes($postjson[usernameDB]);
        $escapedabout =addslashes($postjson[aboutDB]);
        $escapedwebsite =addslashes($postjson[websiteDB]);
        $escapedemail =addslashes($postjson[emailDB]);
        $escapedphone =addslashes($postjson[phoneDB]);

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "UPDATE useraccount SET
        username = '$escapedusername',
        about = '$escapedabout',
        user_email = '$escapedemail',
        user_website = '$escapedwebsite',
        user_phonenumber = '$escapedphone'

        WHERE user_id = '$postjson[globaluseridDB]'
        ");

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************UPDATE PROFILE DETAILS WITHOUT PROFILE PIC**************************************************/









/******************************************************************UPDATE PROFILE DETAILS WITH PROFILE PIC*****************************************/
    //if the function we are  calling from the project is 'updateprofilewithoutprofilepic' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'updateprofilewithprofilepic'){
        $escapedusername =addslashes($postjson[usernameDB]);
        $escapedabout =addslashes($postjson[aboutDB]);
        $escapedwebsite =addslashes($postjson[websiteDB]);
        $escapedemail =addslashes($postjson[emailDB]);
        $escapedphone =addslashes($postjson[phoneDB]);

        //converting profile pic
        //$imagesaveddate = date('Y-m-d'); //this generates at time and date and attaches it to the saved image's name to distinguish images
        $saveimagetime = date('H-i-s');  //this generates at time and attaches it to the saved image's name to distinguish images
        $entry = base64_decode($postjson['profilepicDB']);
        $img = imagecreatefromstring($entry);
        $directory = "userprofilepics/img_user".$saveimagetime.".jpg";  //save picture to folder server and name it as img_user.jpg inside /images folder in the camfilapi folder
        imagejpeg($img, $directory);
        imagedestroy($img);

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "UPDATE useraccount SET
        username = '$escapedusername',
        profile_pic = '$directory',
        about = '$escapedabout',
        user_email = '$escapedemail',
        user_website = '$escapedwebsite',
        user_phonenumber = '$escapedphone'

        WHERE user_id = '$postjson[globaluseridDB]'
        ");

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************UPDATE PROFILE DETAILS WITH PROFILE PIC**************************************************/








/**************************************************************DISPLAY USER DM CHAT LIST****************************************************/

    // else if the function we are  calling from the project is 'displayuserdmchats' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displayuserdmchats'){
        //create array to pick row values from database
        $data = array();

        //read all messages statement
        $query = mysqli_query($mysqli, "SELECT a.id, a.user1_id, b.username AS user1username, b.profile_pic AS user1profilepic, a.user2_id, c.username AS user2username, c.profile_pic AS user2profilepic,  a.lastmsg_id, d.dm_msg, d.dm_msg_time, a.permission_status 

        FROM  lastdmmessage a
        
        JOIN useraccount b on a.user1_id = b.user_id
        
        JOIN useraccount c on a.user2_id = c.user_id
        
        JOIN dm d on a.lastmsg_id = d.dm_msg_id
        
        WHERE a.user1_id = '$postjson[globaluseridDB]' OR a.user2_id = '$postjson[globaluseridDB]' ORDER BY lastmsg_id DESC"); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'row_id_fetched' => $row['id'],
                'user1_id_fetched' => $row['user1_id'],
                'user1_username_fetched' => $row['user1username'],
                'user1_profilepic_fetched' => $row['user1profilepic'],
                'user2_id_fetched' => $row['user2_id'],
                'user2_username_fetched' => $row['user2username'],
                'user2_profilepic_fetched' => $row['user2profilepic'],
                'lastmsg_id_fetched' => $row['lastmsg_id'],
                'lastmsg_fetched' => $row['dm_msg'],
                'lastmsg_time_fetched' => $row['dm_msg_time'],
                'permission_status_fetched' => $row['permission_status']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************DISPLAY USER DM CHATS LIST****************************************************/







/**************************************************************DISPLAY DM CHAT MESSAGES BTN LOGGED IN USER AND ANOTHER USER****************************************************/

    // else if the function we are  calling from the project is 'displaydmmsgsbtn2users' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displaydmmsgsbtn2users'){
        //create array to pick row values from database
        $data = array();

        //read all messages statement
        $query = mysqli_query($mysqli, "SELECT a.dm_msg_id, a.loggedinuser_id, a.user2_id, a.dm_msg, a.dm_msg_media, 
        a.dm_audio_recorded, a.dm_audio_duration, a.dm_msg_time, a.dm_video, a.dm_video_thumbnail, a.dm_audio_name, 
        a.dm_file_name, a.dm_file_path, a.dm_file_mime_type, a.dm_file_size from dm a 
        JOIN useraccount b on a.loggedinuser_id = b.user_id 
        JOIN useraccount c on a.user2_id = c.user_id 
        WHERE (a.loggedinuser_id= '$postjson[loggedinuseridDB]' OR a.loggedinuser_id= '$postjson[receipientuseridDB]') AND (a.user2_id ='$postjson[loggedinuseridDB]' OR a.user2_id ='$postjson[receipientuseridDB]')"); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'dm_msg_id_fetched' => $row['dm_msg_id'],
                'loggedinuser_id_fetched' => $row['loggedinuser_id'],
                'user2_id_fetched' => $row['user2_id'],
                'dm_msg_fetched' => $row['dm_msg'],
                'dm_msg_time_fetched' => $row['dm_msg_time'],
                'dm_msg_media_fetched' => $row['dm_msg_media'],
                'dm_audio_name_fetched' => $row['dm_audio_name'],
                'dm_audio_recorded_fetched' => $row['dm_audio_recorded'],
                'dm_audio_duration_fetched' => $row['dm_audio_duration'],
                'dm_video_fetched' => $row['dm_video'],
                'dm_video_thumbnail_fetched' => $row['dm_video_thumbnail'],
                'dm_file_name_fetched' => $row['dm_file_name'],
                'dm_file_path_fetched' => $row['dm_file_path'],
                'dm_file_mime_type_fetched' => $row['dm_file_mime_type'],
                'dm_file_size_fetched' => $row['dm_file_size']
            );
        }

        // for ($count = 0; $count < sizeof($data) ; $count++) {
        //    echo $data[$count].'dm_msg_fetched';
        // } 

        // for ($i = 0; $i < count($data); $i++)  {
        //     echo $data[$i].dm_msg_fetched;
        // }

        // foreach ($data as $obj) {
        //     $obj->dm_msg_fetched = "damn";
        //  }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************DISPLAY DM CHAT MESSAGES BTN LOGGED IN USER AND ANOTHER USER****************************************************/





/**************************************************************SEARCH IF LAST DM MESSAGE BETWEEN 2 USERS EXISTS****************************************************/

    // else if the function we are  calling from the project is 'searchlastdmrecordbtn2users' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'searchlastdmrecordbtn2users'){
        //create array to pick row values from database
        $data = array();

        //read user messages statement
        $query = mysqli_query($mysqli, "SELECT * FROM lastdmmessage WHERE (user1_id = '$postjson[loggedinuseridDB]' OR user1_id = '$postjson[receipientuseridDB]') AND (user2_id = '$postjson[loggedinuseridDB]' OR user2_id = '$postjson[receipientuseridDB]')");

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'row_id_fetched' => $row['id'],
                'user1_id_fetched' => $row['user1_id'],
                'user2_id_fetched' => $row['user2_id'],
                'lastmsg_id_fetched' => $row['lastmsg_id']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************SEARCH IF LAST DM MESSAGE BETWEEN 2 USERS EXISTS****************************************************/





/******************************************************************CREATE NEW LAST DM MESSAGE RECORD FOR 2 USERS*****************************************/
    //if the function we are  calling from the project is 'insertnewlastdmmessagerecordfor2users' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'insertnewlastdmmessagerecordfor2users'){
        $data = array();

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  lastdmmessage SET
        user1_id   = '$postjson[loggedinuseridDB]',
        user2_id ='$postjson[receipientuseridDB]',
        lastmsg_id = null,
        permission_status = '$postjson[permissionstatusDB]'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $idadd = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'idadd' => $idadd));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************CREATE NEW LAST DM MESSAGE RECORD FOR 2 USERS**************************************************/




/******************************************************************SEND DM MESSAGE WITHOUT MEDIA*****************************************/
    //if the function we are  calling from the project is 'senddmmessage_nopicture' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'senddmmessage_nopicture'){
        $data = array();

        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours
        $messagetime = date('H:i'); //generating our time for the message

        $escapedmsgquotes =addslashes($postjson[dmmessageDB]);

        //now to decrypt our message
        // $cipher_method = 'aes-128-ctr';
        // $enc_key = openssl_digest(php_uname(), 'SHA256', TRUE);
        // $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
        // $crypted_token = openssl_encrypt($escapedmsgquotesdm, $cipher_method, $enc_key, 0, $enc_iv) . "::" . bin2hex($enc_iv);
        // unset($token, $cipher_method, $enc_key, $enc_iv);

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  dm SET
        loggedinuser_id   = '$postjson[loggedinuseridDB]',
        user2_id ='$postjson[receipientuseridDB]',
        dm_msg = '$escapedmsgquotes',
        dm_msg_time = '$messagetime'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd which we'd use to update the lastdmmessage id at the same time for chat between the 2 users
        $lastmsg_id_retrieved = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'lastmsg_id_retrieved' => $lastmsg_id_retrieved));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;

        //now we have retrieved the id of the sent dm message so we use it to update the lastdmmessage field
        $query = mysqli_query($mysqli, "UPDATE lastdmmessage SET
        lastmsg_id   = '$lastmsg_id_retrieved' WHERE  (user1_id = '$postjson[loggedinuseridDB]' OR user1_id = '$postjson[receipientuseridDB]') AND (user2_id = '$postjson[loggedinuseridDB]' OR user2_id = '$postjson[receipientuseridDB]')
        ");
    }
/*************************************************************SEND DM MESSAGE WITHOUT MEDIA**************************************************/




/******************************************************************SEND DM MESSAGE AUDIO ONLY*****************************************/
    //if the function we are  calling from the project is 'senddmmessage_audio' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'senddmmessage_audio'){
        $data = array();

        /**this time generated is the time the audio message was sent and it will be stored in the database */
        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours
        $messagetime = date('H:i'); //generating our time for the message

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  dm SET
        loggedinuser_id   = '$postjson[loggedinuseridDB]',
        user2_id ='$postjson[receipientuseridDB]',
        dm_audio_name = '$postjson[audionameDB]',
        dm_audio_recorded = '$postjson[audiorecordedDB]',
        dm_audio_duration ='$postjson[audiodurationDB]',
        dm_msg_time = '$messagetime'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd which we'd use to update the lastdmmessage id at the same time for chat between the 2 users
        $lastmsg_id_retrieved = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'lastmsg_id_retrieved' => $lastmsg_id_retrieved));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;

        //now we have retrieved the id of the sent dm message so we use it to update the lastdmmessage field
        $query = mysqli_query($mysqli, "UPDATE lastdmmessage SET
        lastmsg_id   = '$lastmsg_id_retrieved' WHERE  (user1_id = '$postjson[loggedinuseridDB]' OR user1_id = '$postjson[receipientuseridDB]') AND (user2_id = '$postjson[loggedinuseridDB]' OR user2_id = '$postjson[receipientuseridDB]')
        ");
    }
/*************************************************************SEND DM MESSAGE AUDIO ONLY**************************************************/







/******************************************************************SEND DM MESSAGE VIDEO ONLY*****************************************/
    //if the function we are  calling from the project is 'senddmmessage_video' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'senddmmessage_video'){
        $data = array();

        /**this time generated is the time the audio message was sent and it will be stored in the database */
        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours
        $messagetime = date('H:i'); //generating our time for the message

        $saveimagetime = date('H-i-s');  //this generates a time and attaches it to the saved image's name to distinguish images
        $entry = base64_decode($postjson['thumbnailDB']);
        $img = imagecreatefromstring($entry);
        $directory = "dmchatsvideothumbnails/thumbnail".$saveimagetime.".jpg";  //save picture to folder server and name it as img_user.jpg inside /images folder in the camfilapi folder
        imagejpeg($img, $directory);
        imagedestroy($img);

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  dm SET
        loggedinuser_id   = '$postjson[loggedinuseridDB]',
        user2_id ='$postjson[receipientuseridDB]',
        dm_video = '$postjson[videoDB]',
        dm_video_thumbnail = '$directory',
        dm_msg_time = '$messagetime'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd which we'd use to update the lastdmmessage id at the same time for chat between the 2 users
        $lastmsg_id_retrieved = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'lastmsg_id_retrieved' => $lastmsg_id_retrieved));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;

        //now we have retrieved the id of the sent dm message so we use it to update the lastdmmessage field
        $query = mysqli_query($mysqli, "UPDATE lastdmmessage SET
        lastmsg_id   = '$lastmsg_id_retrieved' WHERE  (user1_id = '$postjson[loggedinuseridDB]' OR user1_id = '$postjson[receipientuseridDB]') AND (user2_id = '$postjson[loggedinuseridDB]' OR user2_id = '$postjson[receipientuseridDB]')
        ");
    }
/*************************************************************SEND DM MESSAGE VIDEO ONLY**************************************************/






/******************************************************************SEND DM MESSAGE FILE ONLY*****************************************/
    //if the function we are  calling from the project is 'senddmmessage_file' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'senddmmessage_file'){
        $data = array();

        /**this time generated is the time the audio message was sent and it will be stored in the database */
        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours
        $messagetime = date('H:i'); //generating our time for the message

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  dm SET
        loggedinuser_id   = '$postjson[loggedinuseridDB]',
        user2_id ='$postjson[receipientuseridDB]',
        dm_file_name = '$postjson[filenameDB]',
        dm_file_path = '$postjson[filepathDB]',
        dm_file_mime_type ='$postjson[filemimetypeDB]',
        dm_file_size ='$postjson[filesizeDB]',
        dm_msg_time = '$messagetime'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd which we'd use to update the lastdmmessage id at the same time for chat between the 2 users
        $lastmsg_id_retrieved = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'lastmsg_id_retrieved' => $lastmsg_id_retrieved));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;

        //now we have retrieved the id of the sent dm message so we use it to update the lastdmmessage field
        $query = mysqli_query($mysqli, "UPDATE lastdmmessage SET
        lastmsg_id   = '$lastmsg_id_retrieved' WHERE  (user1_id = '$postjson[loggedinuseridDB]' OR user1_id = '$postjson[receipientuseridDB]') AND (user2_id = '$postjson[loggedinuseridDB]' OR user2_id = '$postjson[receipientuseridDB]')
        ");
    }
/*************************************************************SEND DM MESSAGE FILE ONLY**************************************************/







/******************************************************************SEND DM MESSAGE WITH IMAGE MEDIA*****************************************/
    //if the function we are  calling from the project is 'senddmmessage_withpicture' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'senddmmessage_withpicture'){
        $data = array();

        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours
        $messagetime = date('H:i'); //generating our time for the message

        $escapedmsgquotes =addslashes($postjson[dmmessageDB]);

        //$imagesaveddate = date('Y-m-d'); //this generates a time and date and attaches it to the saved image's name to distinguish images
        $saveimagetime = date('H-i-s');  //this generates a time and attaches it to the saved image's name to distinguish images
        $entry = base64_decode($postjson['dmmessage_mediaDB']);
        $img = imagecreatefromstring($entry);
        $directory = "dmmessagemedia/msg_media".$saveimagetime.".jpg";  //save picture to folder server and name it as img_user.jpg inside /images folder in the camfilapi folder
        imagejpeg($img, $directory);
        imagedestroy($img);

        // /**or we can save the image generated into database rather not folder */
        // $imagegenerated = "msg_media".$saveimagetime.".jpg";
        // imagejpeg($img, $imagegenerated);
        // imagedestroy($img);
 

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  dm SET
        loggedinuser_id   = '$postjson[loggedinuseridDB]',
        user2_id ='$postjson[receipientuseridDB]',
        dm_msg = '$escapedmsgquotes',
        dm_msg_time = '$messagetime',
        dm_msg_media = '$directory'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd which we'd use to update the lastdmmessage id at the same time for chat between the 2 users
        $lastmsg_id_retrieved = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'lastmsg_id_retrieved' => $lastmsg_id_retrieved));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;

        //now we have retrieved the id of the sent dm message so we use it to update the lastdmmessage field
        $query = mysqli_query($mysqli, "UPDATE lastdmmessage SET
        lastmsg_id   = '$lastmsg_id_retrieved' WHERE  (user1_id = '$postjson[loggedinuseridDB]' OR user1_id = '$postjson[receipientuseridDB]') AND (user2_id = '$postjson[loggedinuseridDB]' OR user2_id = '$postjson[receipientuseridDB]')
        ");
    }
/*************************************************************SEND DM MESSAGE WITH IMAGE MEDIA**************************************************/






/***************************************************************DELETE DM MESSAGE********************************************************* */
        // else if the function we are  calling from the project is 'delete_dmmessage' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'delete_dmmessage'){
            
            //delete from pinnedmessages table
            $query = mysqli_query($mysqli, "DELETE FROM dm WHERE  dm_msg_id   = '$postjson[message_idDB]' ");
    
            //if query executed show success message
            if($query) {
                $result = json_encode(array('success'=>true)); 
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /***************************************************************DELETE DM MESSAGE********************************************************* */









/******************************************************************SEND EMAIL MESSAGE*****************************************/
    //if the function we are  calling from the project is 'sendemail' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'sendemail'){

        // /********************method 1 ************************************/
        // $receiver_email = $postjson['receiver_emailDB'];        
        // $subject = $postjson['subjectDB'];        
        // $message = $postjson['messageDB']; 

        // //echo $receiver_email;
        // //echo $subject;
        // //echo $message;

        // $success = mail($receiver_email,$subject,$message);
        
        // if (!$success){
        //     echo "Failed to send email. Check your internet connection";
        // }

        // else{
        //     echo "Please check your email, " .$receiver_email. " to reset your credentials";
        // }
        // /********************method 1 ************************************/


        // /********************method 2 ************************************/
        // $email_to = $postjson['receiver_emailDB'];        
        // $email_subject = $postjson['subjectDB'];       
        // $email_message =  $postjson['messageDB']; 
        // $email_from = 'camfila.knust@gmail.com';

        // // create email headers
        // $headers = 'From: '.$email_from."\r\n".
        // 'Reply-To: '.$email_from."\r\n" .
        // 'X-Mailer: PHP/' . phpversion();
        // $success = @mail($email_to, $email_subject, $email_message, $headers);  

        // if (!$success){
        //     echo "fail";
        //     // $errorMessage = error_get_last()['message'];
        //     // echo $errorMessage;
        //     // print_r(error_get_last());

        //     // print_r(error_get_last()['message']);
        // }

        // else{
        //     echo "pass";
        // }
        // /*********************method 2 ************************************/




        /*************************method 3 ********************************** */
        $receiver_email = $postjson['receiver_emailDB'];        
        $subject = $postjson['subjectDB'];        
        $message = $postjson['messageDB']; 

        $mail = new PHPMailer;  //sending the mail using php mailer

        //Server settings
        $mail->SMTPDebug = 0 ;   //useful when testing the script and seeing how it works : 1 to show debug, 0 to hide debug
        $mail->isSMTP();    //set mailer to user smtp
        $mail->Host = 'smtp.gmail.com'; //specific stmp server  : im using google's mail server
        $mail->Port = 587;  // TCP port to connect to
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Username = 'camfila.knust@gmail.com';  // SMTP username : the email addres used to send the message
        $mail->Password = 'CamfilaAdminCs4';  // SMTP password  : the password of the email address

        //Recipients
        $mail->setFrom('camfila.knust@gmail.com', 'CamFila');
        // $mail->From = 'from@example.com';
        // $mail->FromName = 'Mailer';
        // $mail->addAddress('example@gmail.com', 'Receiver Name');     // Add a recipient with name
        $mail->addAddress($receiver_email);               // add a receipient without name
        // $mail->addReplyTo('reply-box@hostinger-tutorials.com', 'Your Name');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        // Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add one attachment
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // add multiple attachments

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        // $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        $mail->Subject = $subject;
        $mail->Body  = $message;
        $mail->AltBody = $message;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Account details have been successfully sent to ' . $receiver_email;
        }
        /*************************method 3 ********************************** */

    }
/*************************************************************SEND EMAIL MESSAGE**************************************************/





/**************************************************************DISPLAY ALL GROUPS****************************************************/

    // else if the function we are  calling from the project is 'displayallgroups' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displayallgroups'){
        //create array to pick row values from database
        $data = array();

        //display all users statement
        $query = mysqli_query($mysqli, "SELECT * FROM groups");

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'group_id_fetched' => $row['group_id'],
                'group_name_fetched' => $row['group_name'],
                'group_profile_pic_fetched' => $row['group_profile_pic'],
                'student_year_eligibility_fetched' => $row['student_year_eligibility'],
                'about_fetched' => $row['about'],
                'members_joined_fetched' => $row['members_joined']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************DISPLAY ALL GROUPS****************************************************/







/**************************************************************LOAD USER'S GROUPS FOLLOWING****************************************************/

    // else if the function we are  calling from the project is 'loadusergroupsfollowing' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'loadusergroupsfollowing'){
        //create array to pick row values from database
        $data = array();

        //read all user followers statement
        $query = mysqli_query($mysqli, "SELECT useraccount.user_id, useraccount.username, groups.group_id, groups.group_name, groups.student_year_eligibility

        FROM group_membership
                
        JOIN useraccount
             ON group_membership.user_id = useraccount.user_id     
                    
        JOIN groups
             ON group_membership.group_id = groups.group_id     
                    
        WHERE group_membership.user_id = '$postjson[globaluseridDB]'"); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'user_id_fetched' => $row['user_id'],
                'username_fetched' => $row['username'],
                'group_id_fetched' => $row['group_id'],
                'group_name_fetched' => $row['group_name'],
                'student_year_eligibility_fetched' => $row['student_year_eligibility']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************LOAD USER'S  GROUPS FOLLOWING****************************************************/








/******************************************************************JOIN GROUP*****************************************/
    //if the function we are  calling from the project is 'joingroup' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'joingroup'){
        $data = array();

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  group_membership SET
        group_id   = '$postjson[group_joining_idDB]',
        user_id ='$postjson[logged_in_useridDB]'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $idadd = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'idadd' => $idadd));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/************************************************************JOIN GROUP**************************************************/




/***************************************************************EXIT GROUP********************************************************* */
        // else if the function we are  calling from the project is 'exitgroup' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'exitgroup'){
            
            //delete from pinnedmessages table
            $query = mysqli_query($mysqli, "DELETE FROM group_membership WHERE  group_id   = '$postjson[group_joining_idDB]'  AND user_id ='$postjson[logged_in_useridDB]' ");
    
            //if query executed show success message
            if($query) {
                $result = json_encode(array('success'=>true)); 
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /***************************************************************EXIT GROUP********************************************************* */



    /***************************************************************DELETE CREATED GROUP********************************************************* */
        // else if the function we are  calling from the project is 'delete_createdgroup' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'delete_createdgroup'){
            
            //delete from pinnedmessages table
            $query = mysqli_query($mysqli, "DELETE FROM groups WHERE  group_id  = '$postjson[group_idDB]' ");
    
            //if query executed show success message
            if($query) {
                $result = json_encode(array('success'=>true)); 
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /***************************************************************DELETE CREATED GROUP********************************************************* */






    /**************************************************************DISPLAY USER GROUP CHAT LIST****************************************************/

    // else if the function we are  calling from the project is 'displayusergroupchats' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displayusergroupchats'){
        //create array to pick row values from database
        $data = array();

        //read all messages statement
        $query = mysqli_query($mysqli, "SELECT d.user_id AS loggedinuser_id, d.username AS loggedinuser_name, e.group_id, e.group_name, e.group_profile_pic, e.members_joined, a.lastmsg_id AS group_lastmsg_id, b.grp_msg AS group_last_message, b.grp_msg_time AS group_lastmsg_time , a.sender_id AS group_lastmsgsender_id, c.username AS group_lastmsgsender_name FROM group_membership 
	
        LEFT OUTER JOIN lastgroupmessage a ON group_membership.group_id = a.group_id 
        
        LEFT OUTER JOIN specific_group_messages b ON a.lastmsg_id = b.grp_msg_id
        
        LEFT OUTER JOIN useraccount c ON a.sender_id = c.user_id
        
        LEFT OUTER JOIN useraccount d ON group_membership.user_id = d.user_id
        
        LEFT OUTER JOIN groups e ON group_membership.group_id = e.group_id
        
        WHERE group_membership.user_id = '$postjson[globaluseridDB]' ORDER BY group_lastmsg_id DESC"); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'loggedinuser_id_fetched' => $row['loggedinuser_id'],
                'loggedinuser_name_fetched' => $row['loggedinuser_name'],
                'group_id_fetched' => $row['group_id'],
                'group_name_fetched' => $row['group_name'],
                'group_profile_pic_fetched' => $row['group_profile_pic'],
                'members_joined_fetched' => $row['members_joined'],
                'group_lastmsg_id_fetched' => $row['group_lastmsg_id'],
                'group_last_message_fetched' => $row['group_last_message'],
                'group_lastmsg_time_fetched' => $row['group_lastmsg_time'],
                'group_lastmsgsender_id_fetched' => $row['group_lastmsgsender_id'],
                'group_lastmsgsender_name_fetched' => $row['group_lastmsgsender_name']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************DISPLAY USER GROUP CHAT  LIST****************************************************/



/**************************************************************SEARCH IF LAST GROUP MESSAGE EXISTS FOR SELECTED GROUP****************************************************/

    // else if the function we are  calling from the project is 'searchlastgroupmessage_forselectedgroup' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'searchlastgroupmessage_forselectedgroup'){
        //create array to pick row values from database
        $data = array();

        //read user messages statement
        $query = mysqli_query($mysqli, "SELECT * FROM lastgroupmessage WHERE group_id = '$postjson[group_idDB]' ");

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'row_id_fetched' => $row['row_id'],
                'group_id_fetched' => $row['group_id'],
                'sender_id_fetched' => $row['sender_id'],
                'lastmsg_id_fetched' => $row['lastmsg_id']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************SEARCH IF LAST GROUP MESSAGE EXISTS FOR SELECTED GROUP****************************************************/



/******************************************************************CREATE NEW LAST GROUP MESSAGE RECORD FOR SELECTED GROUP*****************************************/
    //if the function we are  calling from the project is 'insertnewlastgroupmessagerecordforgroup' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'insertnewlastgroupmessagerecordforgroup'){
        $data = array();

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  lastgroupmessage SET
        group_id   = '$postjson[group_idDB]',
        sender_id = null,
        lastmsg_id = null
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $idadd = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'idadd' => $idadd));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************CREATE NEW LAST GROUP MESSAGE RECORD FOR SELECTED GROUP**************************************************/



/***************************************************************READ SELECTED GROUP'S DETAILS********************************************************* */
        // else if the function we are  calling from the project is 'displaygroupdetails' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'displaygroupdetails'){
            //create array to pick row values from database
            $data = array();
    
            //read from account table
            $query = mysqli_query($mysqli, "SELECT * FROM groups WHERE  group_id = '$postjson[group_idDB]' ");
    
            //php variables on the left..column names on the right
            //we created the php variables here and assigned row values from respective table columns into the variables
            while($row = mysqli_fetch_array($query)){
                $data[]= array(
                    'group_id_fetched' => $row['group_id'],
                    'group_name_fetched' => $row['group_name'],
                    'group_admin_id_fetched' => $row['group_admin_id'],
                    'group_profile_pic_fetched' => $row['group_profile_pic'],
                    'student_year_eligibility_fetched' => $row['student_year_eligibility'],
                    'about_fetched' => $row['about'],
                    'members_joined_fetched' => $row['members_joined'],
                    
                );
            }
    
            //if query executed put all fetched rows from $data array into result
            if($query) {
                $result = json_encode($data);   
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /***************************************************************READ SELECTED GROUP'S DETAILS********************************************************* */



    /**************************************************************DISPLAY GROUP CHAT MESSAGES FOR SPECIFIC GROUP****************************************************/

    // else if the function we are  calling from the project is 'displayspecificgroupmessages' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displayspecificgroupmessages'){
        //create array to pick row values from database
        $data = array();

        //read all messages statement
        $query = mysqli_query($mysqli, "SELECT * FROM specific_group_messages 

        JOIN useraccount on sender_id = useraccount.user_id
        
        WHERE group_id = '$postjson[group_idDB]' "); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'grp_msg_id_fetched' => $row['grp_msg_id'],
                'group_id_fetched' => $row['group_id'],
                'sender_id_fetched' => $row['sender_id'],
                'grp_msg_fetched' => $row['grp_msg'],
                'grp_msg_time_fetched' => $row['grp_msg_time'],
                'grp_msg_media_fetched' => $row['grp_msg_media'],
                'grp_audio_name_fetched' => $row['grp_audio_name'],
                'grp_audio_recorded_fetched' => $row['grp_audio_recorded'],
                'grp_audio_duration_fetched' => $row['grp_audio_duration'],
                'grp_video_fetched' => $row['grp_video'],
                'grp_video_thumbnail_fetched' => $row['grp_video_thumbnail'],
                'grp_file_name_fetched' => $row['grp_file_name'],
                'grp_file_path_fetched' => $row['grp_file_path'],
                'grp_file_mime_type_fetched' => $row['grp_file_mime_type'],
                'grp_file_size_fetched' => $row['grp_file_size'],
                'user_id_fetched' => $row['user_id'],
                'username_fetched' => $row['username'],
                'userpassword_fetched' => $row['userpassword'],
                'profile_pic_fetched' => $row['profile_pic'],
                'about_fetched' => $row['about'],
                'date_joined_fetched' => $row['date_joined']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************DISPLAY GROUP CHAT MESSAGES FOR SPECIFIC GROUP****************************************************/





/******************************************************************SEND GROUP MESSAGE WITHOUT MEDIA*****************************************/
    //if the function we are  calling from the project is 'sendgroupmessage_nopicture' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'sendgroupmessage_nopicture'){
        $data = array();

        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours
        $messagetime = date('H:i'); //generating our time for the message

        $escapedmsgquotes =addslashes($postjson[groupmessageDB]);

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  specific_group_messages SET
        group_id   = '$postjson[group_idDB]',
        sender_id ='$postjson[loggedinuseridDB]',
        grp_msg = '$escapedmsgquotes',
        grp_msg_time = '$messagetime'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd which we'd use to update the lastgroupmessage id at the same time for group we are chatting in
        $lastmsg_id_retrieved = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'lastmsg_id_retrieved' => $lastmsg_id_retrieved));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;

        //now we have retrieved the id of the sent group message so we use it to update the lastgroupmessage field
        $query = mysqli_query($mysqli, "UPDATE lastgroupmessage SET
        lastmsg_id   = '$lastmsg_id_retrieved',
        sender_id = '$postjson[loggedinuseridDB]'
        WHERE  (group_id = '$postjson[group_idDB]')
        ");
    }
/*************************************************************SEND GROUP MESSAGE WITHOUT MEDIA**************************************************/




/******************************************************************SEND GROUP MESSAGE WITH MEDIA*****************************************/
    //if the function we are  calling from the project is 'sendgroupmessage_withpicture' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'sendgroupmessage_withpicture'){
        $data = array();

        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours
        $messagetime = date('H:i'); //generating our time for the message

        $escapedmsgquotes =addslashes($postjson[groupmessageDB]);

        //$imagesaveddate = date('Y-m-d'); //this generates a time and date and attaches it to the saved image's name to distinguish images
        $saveimagetime = date('H-i-s');  //this generates a time and attaches it to the saved image's name to distinguish images
        $entry = base64_decode($postjson['grpmessage_mediaDB']);
        $img = imagecreatefromstring($entry);
        $directory = "groupmessagemedia/msg_media".$saveimagetime.".jpg";  //save picture to folder server and name it as img_user.jpg inside /images folder in the camfilapi folder
        imagejpeg($img, $directory);
        imagedestroy($img);

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  specific_group_messages SET
        group_id   = '$postjson[group_idDB]',
        sender_id ='$postjson[loggedinuseridDB]',
        grp_msg = '$escapedmsgquotes',
        grp_msg_time = '$messagetime',
        grp_msg_media = '$directory'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd which we'd use to update the lastgroupmessage id at the same time for group we are chatting in
        $lastmsg_id_retrieved = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'lastmsg_id_retrieved' => $lastmsg_id_retrieved));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;

        //now we have retrieved the id of the sent group message so we use it to update the lastgroupmessage field
        $query = mysqli_query($mysqli, "UPDATE lastgroupmessage SET
        lastmsg_id   = '$lastmsg_id_retrieved',
        sender_id = '$postjson[loggedinuseridDB]'
        WHERE  (group_id = '$postjson[group_idDB]')
        ");
    }
/*************************************************************SEND GROUP MESSAGE WITH MEDIA**************************************************/




/******************************************************************SEND GROUP MESSAGE AUDIO ONLY*****************************************/
    //if the function we are  calling from the project is 'sendgroupmessage_audio' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'sendgroupmessage_audio'){
        $data = array();

        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours
        $messagetime = date('H:i'); //generating our time for the message


        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  specific_group_messages SET
        group_id   = '$postjson[group_idDB]',
        sender_id ='$postjson[loggedinuseridDB]',
        grp_audio_name = '$postjson[audionameDB]',
        grp_audio_recorded = '$postjson[audiorecordedDB]',
        grp_audio_duration ='$postjson[audiodurationDB]',
        grp_msg_time = '$messagetime'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd which we'd use to update the lastgroupmessage id at the same time for group we are chatting in
        $lastmsg_id_retrieved = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'lastmsg_id_retrieved' => $lastmsg_id_retrieved));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;

        //now we have retrieved the id of the sent group message so we use it to update the lastgroupmessage field
        $query = mysqli_query($mysqli, "UPDATE lastgroupmessage SET
        lastmsg_id   = '$lastmsg_id_retrieved',
        sender_id = '$postjson[loggedinuseridDB]'
        WHERE  (group_id = '$postjson[group_idDB]')
        ");
    }
/*************************************************************SEND GROUP MESSAGE AUDIO ONLY**************************************************/







/******************************************************************SEND GROUP MESSAGE VIDEO ONLY*****************************************/
    //if the function we are  calling from the project is 'sendgroupmessage_video' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'sendgroupmessage_video'){
        $data = array();

        /**this time generated is the time the audio message was sent and it will be stored in the database */
        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours
        $messagetime = date('H:i'); //generating our time for the message

        $saveimagetime = date('H-i-s');  //this generates a time and attaches it to the saved image's name to distinguish images
        $entry = base64_decode($postjson['thumbnailDB']);
        $img = imagecreatefromstring($entry);
        $directory = "groupchatsvideothumbnails/thumbnail".$saveimagetime.".jpg";  //save picture to folder server and name it as img_user.jpg inside /images folder in the camfilapi folder
        imagejpeg($img, $directory);
        imagedestroy($img);

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  specific_group_messages SET
        sender_id   = '$postjson[loggedinuseridDB]',
        group_id ='$postjson[group_idDB]',
        grp_video = '$postjson[videoDB]',
        grp_video_thumbnail = '$directory',
        grp_msg_time = '$messagetime'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd which we'd use to update the lastdmmessage id at the same time for chat between the 2 users
        $lastmsg_id_retrieved = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'lastmsg_id_retrieved' => $lastmsg_id_retrieved));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;

        //now we have retrieved the id of the sent group message so we use it to update the lastgroupmessage field
        $query = mysqli_query($mysqli, "UPDATE lastgroupmessage SET
        lastmsg_id   = '$lastmsg_id_retrieved',
        sender_id = '$postjson[loggedinuseridDB]'
        WHERE  (group_id = '$postjson[group_idDB]')
        ");
    }
/*************************************************************SEND GROUP MESSAGE VIDEO ONLY**************************************************/







/******************************************************************SEND GROUP MESSAGE FILE ONLY*****************************************/
    //if the function we are  calling from the project is 'sendgroupmessage_file' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'sendgroupmessage_file'){
        $data = array();

        date_default_timezone_set('Africa/Accra');  //setting the time zone to ours
        $messagetime = date('H:i'); //generating our time for the message


        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  specific_group_messages SET
        group_id   = '$postjson[group_idDB]',
        sender_id ='$postjson[loggedinuseridDB]',
        grp_file_name = '$postjson[filenameDB]',
        grp_file_path = '$postjson[filepathDB]',
        grp_file_mime_type ='$postjson[filemimetypeDB]',
        grp_file_size ='$postjson[filesizeDB]',
        grp_msg_time = '$messagetime'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd which we'd use to update the lastgroupmessage id at the same time for group we are chatting in
        $lastmsg_id_retrieved = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'lastmsg_id_retrieved' => $lastmsg_id_retrieved));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;

        //now we have retrieved the id of the sent group message so we use it to update the lastgroupmessage field
        $query = mysqli_query($mysqli, "UPDATE lastgroupmessage SET
        lastmsg_id   = '$lastmsg_id_retrieved',
        sender_id = '$postjson[loggedinuseridDB]'
        WHERE  (group_id = '$postjson[group_idDB]')
        ");
    }
/*************************************************************SEND GROUP MESSAGE FILE ONLY**************************************************/






/***************************************************************DELETE GROUP MESSAGE********************************************************* */
        // else if the function we are  calling from the project is 'delete_groupmessage' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'delete_groupmessage'){
            
            //delete from pinnedmessages table
            $query = mysqli_query($mysqli, "DELETE FROM specific_group_messages WHERE  grp_msg_id   = '$postjson[message_idDB]' ");
    
            //if query executed show success message
            if($query) {
                $result = json_encode(array('success'=>true)); 
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /***************************************************************DELETE GROUP MESSAGE********************************************************* */









/******************************************************************UPDATE ACCOUNT PASSWORD*****************************************/
    //if the function we are  calling from the project is 'updateaccountpassword' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'updateaccountpassword'){
        $escapedpasswordquotes =addslashes($postjson[newpasswordDB]);

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "UPDATE useraccount SET
        userpassword = '$escapedpasswordquotes'
        WHERE user_id = '$postjson[globaluseridDB]'
        ");

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************UPDATE ACCOUNT PASSWORD**************************************************/





 /**************************************************************DISPLAY SPECIFIC GROUP'S MEMBERS DETAILS****************************************************/

    // else if the function we are  calling from the project is 'displayspecificgroup_groupmembersdetails' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displayspecificgroup_groupmembersdetails'){
        //create array to pick row values from database
        $data = array();

        //read all messages statement
        $query = mysqli_query($mysqli, "SELECT a.user_id, a.username, a.profile_pic, a.about, b.group_id  FROM group_membership b

        JOIN useraccount a on b.user_id = a.user_id
        
        WHERE group_id = '$postjson[group_idDB]' "); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'user_id_fetched' => $row['user_id'],
                'username_fetched' => $row['username'],
                'profile_pic_fetched' => $row['profile_pic'],
                'about_fetched' => $row['about'],
                'group_id_fetched' => $row['group_id']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************DISPLAY SPECIFIC GROUP'S MEMBERS DETAILS****************************************************/







 /**************************************************************DISPLAY SPECIFIC GROUP EACH MEMBER'S MESSAGE COUNT****************************************************/

    // else if the function we are  calling from the project is 'displayspecificgroup_eachmember_messagescount' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'displayspecificgroup_eachmember_messagescount'){
        //create array to pick row values from database
        $data = array();

        //read all messages statement
        $query = mysqli_query($mysqli, "SELECT sender_id, b.username AS sender_name, a.group_id ,COUNT(*) AS messages_posted FROM specific_group_messages a

        JOIN useraccount b ON b.user_id = a.sender_id
        
        WHERE group_id =  '$postjson[group_idDB]' 
        
        GROUP BY sender_id "); 

        //php variables on the left..column names on the right
        //we created the php variables here and assigned row values from respective table columns into the variables
        while($row = mysqli_fetch_array($query)){
            $data[]= array(
                'sender_id_fetched' => $row['sender_id'],
                'sender_name_fetched' => $row['sender_name'],
                'group_id_fetched' => $row['group_id'],
                'messages_posted_fetched' => $row['messages_posted']
            );
        }

        //if query executed put all fetched rows from $data array into result
        if($query) {
            $result = json_encode($data);   
        }
        //else if query didnt execute
        else {
            $result = json_encode(array('success'=>false)); 
        }

        //finally output whatever was saved into result
        echo $result;
    }
/**************************************************************DISPLAY SPECIFIC GROUP EACH MEMBER'S MESSAGE COUNT****************************************************/




/******************************************************************UPDATE GROUP MEMBERS COUNT OF SELECTED GROUP*****************************************/
    //if the function we are  calling from the project is 'updategroup_memberscount' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'updategroup_memberscount'){

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "UPDATE groups SET
        members_joined = '$postjson[members_joinedDB]'
        WHERE group_id = '$postjson[group_idDB]'
        ");

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*************************************************************UPDATE GROUP MEMBERS COUNT OF SELECTED GROUP**************************************************/





/***************************************************************DELETE USER ACCOUNT********************************************************* */
        // else if the function we are  calling from the project is 'deletemessage' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'deleteuseraccount'){
            
            //delete from messages table
            $query = mysqli_query($mysqli, "DELETE FROM useraccount WHERE  user_id = '$postjson[globaluserid]' ");
    
            //if query executed show success message
            if($query) {
                $result = json_encode(array('success'=>true)); 
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /***************************************************************DELETE USER ACCOUNT********************************************************* */




    /*********************************************************UPDATE USER DM CHAT PERMISSION STATUS*****************************************/
    //if the function we are  calling from the project is 'updateuserdmchatpermissionstatus' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'updateuserdmchatpermissionstatus'){

        //passing values from variables in project to table columns
        //columns names on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "UPDATE lastdmmessage SET
        permission_status   = '$postjson[permissionstatusDB]'  WHERE  (user1_id = '$postjson[loggedinuseridDB]' OR user1_id = '$postjson[receipientuseridDB]') AND (user2_id = '$postjson[loggedinuseridDB]' OR user2_id = '$postjson[receipientuseridDB]')
        ");

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        echo $result;
    }
/*********************************************************UPDATE USER DM CHAT PERMISSION STATUS*****************************************/







/***************************************************************SEARCH REFERENCE NUMBER FOR PASSWORD RESET********************************************************* */
        // else if the function we are  calling from the project is 'searchrefnumber' then peform the followin db commands
        elseif($postjson['mydbfunc'] == 'searchrefnumber'){
            //create array to pick row values from database
            $data = array();
    
            //read from account table
            $query = mysqli_query($mysqli, "SELECT * FROM useraccount WHERE  refnumber  = '$postjson[refnumberDB]' ");
    
            //php variables on the left..column names on the right
            //we created the php variables here and assigned row values from respective table columns into the variables
            while($row = mysqli_fetch_array($query)){
                $data[]= array(
                    'user_id_fetched' => $row['user_id'],
                    'refnumber_fetched' => $row['refnumber'],
                    'username_fetched' => $row['username'],
                    'userpassword_fetched' => $row['userpassword'],
                    'profile_pic_fetched' => $row['profile_pic'],
                    'about_fetched' => $row['about'],
                    'user_phonenumber_fetched' => $row['user_phonenumber'],
                    'user_email_fetched' => $row['user_email'],
                    'user_website_fetched' => $row['user_website'],
                    'date_joined_fetched' => $row['date_joined'],
                    'default_group_id_fetched' => $row['default_group_id'],
                );
            }
    
            //if query executed put all fetched rows from $data array into result
            if($query) {
                $result = json_encode($data);   
            }
            //else if query didnt execute
            else {
                $result = json_encode(array('success'=>false)); 
            }
    
            //finally output whatever was saved into result
            echo $result;
        }
    /***************************************************************SEARCH REFERENCE NUMBER FOR PASSWORD RESET ********************************************************* */




    /******************************************************************CREATE GROUUP (with profile pic)*****************************************/
    //if the function we are  calling from the project is 'createnewgroup_withprofilepic' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'createnewgroup_withprofilepic'){
        $data = array();

        $escapedgroupnamequotes =addslashes($postjson['groupnameDB']);
        $escapedgroupdescriptionquotes =addslashes($postjson['groupdescriptionDB']);

        /*********************************************12 */
        //$imagesaveddate = date('Y-m-d'); //this generates at time and date and attaches it to the saved image's name to distinguish images
        $saveimagetime = date('H-i-s');  //this generates at time and attaches it to the saved image's name to distinguish images
        $entry = base64_decode($postjson['groupprofilepicDB']);
        $img = imagecreatefromstring($entry);
        $directory = "createdgroupprofilepics/img_group".$saveimagetime.".jpg";  //save picture to folder server and name it as img_user.jpg inside /images folder in the camfilapi folder
        imagejpeg($img, $directory);
        imagedestroy($img);

        //passing values from variables in project to table columns
        //columns values on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  groups SET
        group_name   = '$escapedgroupnamequotes',
        group_profile_pic = '$directory', 
        student_year_eligibility = null,
        about ='$escapedgroupdescriptionquotes',
        members_joined =  '1',
        group_admin_id = '$postjson[loggedinuseridDB]'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $groupidretrieved = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'groupidretrieved' => $groupidretrieved));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        // echo $result;

        echo $groupidretrieved; //we want to return the id of the new created group so that we can allow the admin of the created group to add new members using this group id

        //after fetching our group id we add a new record to the group membership table where we add the user's id and the group's id
        $query = mysqli_query($mysqli, "INSERT INTO  group_membership SET
        group_id = '$groupidretrieved',
        user_id   = '$postjson[loggedinuseridDB]'
        ");


    }
/*************************************************************CREATE GROUP (with profile pic)**************************************************/






 /******************************************************************CREATE GROUUP (without profile pic)*****************************************/
    //if the function we are  calling from the project is 'createnewgroup_withoutprofilepic' then peform the followin db commands
    elseif($postjson['mydbfunc'] == 'createnewgroup_withoutprofilepic'){
        $data = array();

        $escapedgroupnamequotes =addslashes($postjson['groupnameDB']);
        $escapedgroupdescriptionquotes =addslashes($postjson['groupdescriptionDB']);

        //passing values from variables in project to table columns
        //columns values on the left...project variables imported from project using post are on the right
        $query = mysqli_query($mysqli, "INSERT INTO  groups SET
        group_name   = '$escapedgroupnamequotes',
        student_year_eligibility = null,
        about ='$escapedgroupdescriptionquotes',
        members_joined =  '1',
        group_admin_id = '$postjson[loggedinuseridDB]'
        ");

        //fetch the id or PK of the row inserted and assign it to $idadd
        $groupidretrieved = mysqli_insert_id($mysqli);        

        // if query executed fetch only id into result
        if($query){
            $result = json_encode(array('success'=>true, 'groupidretrieved' => $groupidretrieved));   //display the value in the console
        } 
        //if query didnt execute
        else {
            $result = json_encode(array('success'=>false));
        }

        //finally output whatever we saved into result
        // echo $result;

        echo $groupidretrieved; //we want to return the id of the new created group so that we can allow the admin of the created group to add new members using this group id

        //after fetching our group id we add a new record to the group membership table where we add the user's id and the group's id
        $query = mysqli_query($mysqli, "INSERT INTO  group_membership SET
        group_id = '$groupidretrieved',
        user_id   = '$postjson[loggedinuseridDB]'
        ");


    }
/*************************************************************CREATE GROUP (without profile pic)**************************************************/







?>



