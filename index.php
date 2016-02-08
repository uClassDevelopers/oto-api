<?php
/*
 * Copyright (C) 2015 uClass Developers Daniel Holm & Adam Jacobs Feldstein
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

// Stop/fix cors error
header("Access-Control-Allow-Origin: *");

// If api key is correct connect to database and make sure it is coded in utf8
if ($_GET['apikey'] == "vV85LEH2cUJjshrFx5") {

    header("Content-type: application/json; charset=utf-8");
    $servername = "127.0.0.1";
    $username = "root";  //Change
    $password = ""; //Change
    $database = "oto"; //Change
    $dbprefix = "wpoto_"; //Change
    $baseURL = "https://oto.com/"; //Change
    mysqli_set_charset("utf8");
    $con=mysqli_connect("$servername",$username, $password, $database); 
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    if ($_GET['oto_directory'] == "1") {
        $query = "SELECT * FROM `".$dbprefix."oto_directory` WHERE activated=1 ORDER BY `id` ASC";
        mysqli_query("SET names utf8;");
        mysqli_set_charset("utf8");
        mysqli_set_charset('utf8');
        $rs = mysqli_query($con,$query);

        if (mysqli_num_rows($rs) > 0) {
            $response["oto_directory"]= array();

            //Construct the oto_directory array    
            while($obj = mysqli_fetch_array($rs)) {

                $info = array(
                    "id" => $obj['id'], "title" => utf8_encode($obj['title']), "school_logo" => utf8_encode($obj['school_logo']), "lang" => utf8_encode($obj['lang']), "school_name" => utf8_encode($obj['school_name']), "school_id" => utf8_encode($obj['school_id']), "school_domain" => utf8_encode($obj['school_domain'])
                );
                array_push($response["oto_directory"], $info);
            }

            $qLang = "SELECT * FROM `".$dbprefix."oto_language` ORDER BY `id` ASC";
            $rsLang = mysqli_query($con,$qLang);

            if (mysqli_num_rows($rsLang) > 0) {
                $langArray["oto_language"]= array();

                //Construct the oto_lang array    
                while($obj = mysqli_fetch_array($rsLang)) {
                    $Lang = array(
                        "id" => $obj['id'], "language" => utf8_encode($obj['language']), "original" => utf8_encode($obj['original']), "translation" => utf8_encode($obj['translation']), "location" => utf8_encode($obj['location'])
                    );
                    array_push($langArray["oto_language"], $Lang);
                }
            }

            //Loop through all schools     
            foreach($response['oto_directory'] as $index => $dir) {
                //Loop through all posts in the wordpress api for courses category
                foreach($langArray["oto_language"] as $p => $arr) {
                    //Loop through the posts categories

                    // If the category id matches the course id push the posts in to elments array ´
                    if($dir['lang'] == $arr['language']) {               
                        array_push($response['oto_directory'][$index]['oto_language'][] = $arr);
                    }

                }
            }

            $response["success"] = 1;
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            $response["message"] = "No sites found";
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);
        }
    }  
    else if ($_GET['oto_language'] == "1") {
        $query = "SELECT * FROM `".$dbprefix."oto_language` ORDER BY `id` ASC";
        mysqli_query("SET names utf8;");
        mysqli_set_charset("utf8");
        mysqli_set_charset('utf8');
        $rs = mysqli_query($con,$query);

        if (mysqli_num_rows($rs) > 0) {
            $response["oto_language"]= array();

            while($obj = mysqli_fetch_array($rs)) {
                $info = array(
                    "id" => $obj['id'], "language" => utf8_encode($obj['language']), "original" => utf8_encode($obj['original']), "translation" => utf8_encode($obj['translation']), "location" => utf8_encode($obj['location'])
                );
                array_push($response["oto_language"], $info);
            }

            $response["success"] = 1;
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            $response["message"] = "No sites found";
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);
        }
    } 
    else if ($_GET['courses-slider'] == "1") {
        $query = "SELECT * FROM `".$dbprefix."oto_courses_slider` ORDER BY `id` ASC";
        mysqli_query("SET names utf8;");
        mysqli_set_charset("utf8");
        mysqli_set_charset('utf8');
        $rs = mysqli_query($con,$query);

        if (mysqli_num_rows($rs) > 0) {
            $response["courses_slider"]= array();


            while($obj = mysqli_fetch_array($rs)) {
                $info = array(
                    "id" => $obj['id'], "title" => utf8_encode($obj['title']), "course" => utf8_encode($obj['course']), "image_url" => utf8_encode($obj['image_url']), "on_link" => utf8_encode($obj['on_link']), "row" => $obj['row'], "position" => $obj['position'], "description" => utf8_encode($obj['content'])

                );
                array_push($response["courses_slider"], $info);
            }

            $response["success"] = 1;
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            $response["message"] = "Inga slides hittades";
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);
        }
    }
    else if($_GET['list-taxonomy']== '1') {
        if ($_GET['type'] == 'category') {
            $query = "SELECT * FROM `".$dbprefix."term_taxonomy` WHERE taxonomy='category' AND parent != 43 AND term_id !=43 AND term_id !=30 AND term_id !=1 ORDER BY `term_id` ASC";
        } else if ($_GET['type'] == 'tag') {

            $query = "SELECT * FROM `".$dbprefix."term_taxonomy` WHERE taxonomy='post_tag' ORDER BY `term_id` ASC";
        }
        mysqli_query("SET names utf8;");
        mysqli_set_charset("utf8");
        mysqli_set_charset('utf8');
        $rs = mysqli_query($con,$query);

        if (mysqli_num_rows($rs) > 0) {
            $response["list_taxonomy"]= array();

            while($obj = mysqli_fetch_array($rs)) {
                $s_query = "SELECT * FROM `".$dbprefix."terms` WHERE term_id=".$obj['term_id']." ORDER BY `term_id` ASC";
                mysqli_query("SET names utf8;");
                mysqli_set_charset("utf8");
                mysqli_set_charset('utf8');
                $rs2 = mysqli_query($con,$s_query);
                while($objs = mysqli_fetch_array($rs2)) {
                    $info = array("id" => $obj['term_id'], "description" => utf8_encode($obj['description']), "name" => utf8_encode($objs['name']), "parent" => utf8_encode($obj['parent']), "post_count" => utf8_encode($obj['count']), "slug" => utf8_encode($objs['slug'])
                                 );
                    array_push($response["list_taxonomy"], $info);  
                }    
            }
            $response["success"] = 1;
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);   
        } else {
            $response["success"] = 0;
            $response["message"] = "Inget hittades";
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);
        }   
    } 
    else if($_GET['post_vote'] == "1") {
        if ($_GET['post_id'] > 0) {

            if ($_GET['new_vote'] == "1") {
                //Insert to vote table, prepare the query
                $query = "INSERT INTO `rudbeck_info`.`".$dbprefix."wti_like_post` (`id`, `post_id`, `value`, `date_time`, `ip`, `user_id`) VALUES (NULL, '".$_GET['post_id']."', '1', '', '0', '0');";

                $rs = mysqli_query($con,$query);
            }

            $query2 = "SELECT * FROM `rudbeck_info`.`".$dbprefix."wti_like_post` where post_id=".$_GET['post_id']."";
            mysqli_query("SET names utf8;");
            mysqli_set_charset("utf8");
            mysqli_set_charset('utf8');
            $rs = mysqli_query($con,$query2);

            $mysql_count_result = mysqli_num_rows($rs);

            $response["success"] = 1;
            $response["num_votes"] =  $mysql_count_result;

            echo json_encode($response);

        } else {
            $response["success"] = 0;
            $response["message"] = "403 FELAKTIGA PARAMETRAR";

            echo json_encode($response);
        }
    }
    else if ($_GET['list-all-courses'] == "1") {
        $query = "SELECT * FROM `".$dbprefix."term_taxonomy` WHERE parent=".$_GET['parent']." ORDER BY `term_id` ASC";
        mysqli_query("SET names utf8;");
        mysqli_set_charset("utf8");
        mysqli_set_charset('utf8');
        $rs = mysqli_query($con,$query);

        $all_courses_posts_url= "".$baseURL."category/kurser/?json=1&count=10&apikey=ErtYnDsKATCzmuf6";
        $json_elements = file_get_contents($all_courses_posts_url);
        $elements_arr = json_decode($json_elements, TRUE);
        //print_r($elements_arr);

        if (mysqli_num_rows($rs) > 0) {
            $response["list_all_courses"]= array();

            while($obj = mysqli_fetch_array($rs)) {
                $s_query = "SELECT * FROM `".$dbprefix."terms` WHERE term_id=".$obj['term_id']." ORDER BY `term_id` ASC";
                mysqli_query("SET names utf8;");
                mysqli_set_charset("utf8");
                mysqli_set_charset('utf8');
                $rs2 = mysqli_query($con,$s_query);
                while($objs = mysqli_fetch_array($rs2)) {
                    $info = array(
                        "id" => $obj['term_id'], "description" => utf8_encode($obj['description']), "name" => utf8_encode($objs['name']), "parent" => utf8_encode($obj['parent']), "post-count" => utf8_encode($obj['count']), "slug" => utf8_encode($objs['slug'])

                    );
                    array_push($response["list_all_courses"], $info);  
                }    
            }
            $response["success"] = 1;
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            //print_r($response);

            //Loop through all courses     
            foreach($response['list_all_courses'] as $index => $course) {
                //Loop through all posts in the wordpress api for courses category
                foreach($elements_arr[posts] as $p => $arr) {
                    //Loop through the posts categories
                    foreach($arr[categories] as $arr2 => $cat) {
                        // If the category id matches the course id push the posts in to elments array ´
                        if($course['id'] == $cat['id']) {               
                            array_push($response['list_all_courses'][$index]['elements'][] = $arr);
                        }
                    }
                }
            }
            //encode the respone in json format
            echo json_encode($response);
        }
        else {
            $response["success"] = 0;
            $response["message"] = "Inga kurser hittades";
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);
        }   
    } else if($_GET['list-courses-name'] == "1"){
        $query = "SELECT * FROM `".$dbprefix."term_taxonomy` WHERE parent=".$_GET['parent']." ORDER BY `term_id` ASC";
        mysqli_query("SET names utf8;");
        mysqli_set_charset("utf8");
        mysqli_set_charset('utf8');
        $rs = mysqli_query($con,$query);

        if (mysqli_num_rows($rs) > 0) {
            $response["list_courses"]= array();

            while($obj = mysqli_fetch_array($rs)) {
                $s_query = "SELECT * FROM `wp_terms` WHERE term_id=".$obj['term_id']." ORDER BY `term_id` ASC";
                mysqli_query("SET names utf8;");
                mysqli_set_charset("utf8");
                mysqli_set_charset('utf8');
                $rs2 = mysqli_query($con,$s_query);
                while($objs = mysqli_fetch_array($rs2)) {
                    $info = array("id" => $obj['term_id'], "description" => utf8_encode($obj['description']), "name" => utf8_encode($objs['name']), "parent" => utf8_encode($obj['parent']), "post-count" => utf8_encode($obj['count']), "slug" => utf8_encode($objs['slug'])
                                 );
                    array_push($response["list_courses"], $info);  
                }    
            }
            $response["success"] = 1;
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);   
        }
        else {
            $response["success"] = 0;
            $response["message"] = "Inga kurser hittades";
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);
        }
    } else if($_GET['startpage']='1') {
        $query = "SELECT * FROM `".$dbprefix."oto_start` ORDER BY id ASC";
        mysqli_query("SET names utf8;");
        mysqli_set_charset("utf8");
        mysqli_set_charset('utf8');
        $rs = mysqli_query($con,$query);

        if (mysqli_num_rows($rs) > 0) {
            $response["startpage"]= array();

            while($obj = mysqli_fetch_array($rs)) {
                $info = array(
                    "id" => $obj['id'], "title" => utf8_encode($obj['title']), "content" => utf8_encode($obj['content']), "image_url" => utf8_encode($obj['image_url']), "on_link" => utf8_encode($obj['on_link']),"row" => $obj['row'], "position" => $obj['position'], "is_dyn" => $obj['is_dyn'], "dyn_link" => utf8_encode($obj['dyn_link'])

                );
                array_push($response["startpage"], $info);
            }

            $response["success"] = 1;
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);
        }
        else {

            $response["success"] = 0;
            $response["message"] = "Inga boxar/slides hittades";
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);
        }
    }
}
else {
    $response["success"] = 0;
    $response["message"] = "Felaktiga paramterar";
    header("Content-type: application/json; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    echo json_encode($response);
}
?>