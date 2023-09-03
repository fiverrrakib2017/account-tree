<?php
include 'config.php';

//check the value is data come
if (isset($_POST['add_accounts_data'])) {

    //catch the value and put the variable
    $account_name=$_POST['account_name'];
    $parent_id=$_POST['parent_id'];

    /* Insert the database */
    /* Check the database connection */
    if ($result=$conn->query("INSERT INTO categories(name,parent_id,date)VALUES('$account_name','$parent_id',NOW())")) {
        //check the data insert and result will be response index.php file 
       if ($result==true) {
        echo 1;
       }else{
        echo 0;
       }
    }
    
}