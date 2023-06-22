<?php

require('sh.php');

//local path: http://localhost/apps/book-kings/book-kings/public/tools/scraper/scraperz.php?cmd=scout_1


/*
    -- Scount Link List --
        https://www.goodreads.com/list/tag/romance, //done
        https://www.goodreads.com/list/tag/young-adult, //done
        https://www.goodreads.com/list/tag/fantasy,//done
        https://www.goodreads.com/list/tag/science-fiction, //done
        https://www.goodreads.com/list/tag/non-fiction, //done
        https://www.goodreads.com/list/tag/children, //done
        https://www.goodreads.com/list/tag/history, //done
        https://www.goodreads.com/list/tag/mystery, //done
        https://www.goodreads.com/list/tag/horror, //done
        https://www.goodreads.com/list/tag/historical-fiction, //done
        https://www.goodreads.com/list/tag/best,//done
        https://www.goodreads.com/list/tag/love, //done
        https://www.goodreads.com/list/tag/middle-grade, //done
        https://www.goodreads.com/list/tag/contemporary, //done
        https://www.goodreads.com/list/tag/historical-romance, //done
        https://www.goodreads.com/list/tag/thriller, //done
        https://www.goodreads.com/list/tag/nonfiction, //dpme
        https://www.goodreads.com/list/tag/series, //done
        https://www.goodreads.com/list/tag/classics, //done
        https://www.goodreads.com/list/tag/graphic-novels

*/

#region
#endregion

#region - sample text
// $test = fetchQuery("SELECT author FROM book_data WHERE id = '59'; ");
// $rows = mysqli_fetch_array($test);
// echo $rows['author'];
#endregion

#region - where to get requests
$id = $_POST["id"]; 
$jobType = $_POST["jobType"]; 
$cmd = $_POST["cmd"]; 

$cmd = $_REQUEST["cmd"]; 

//$start_date = $_POST["start_date"]; 
//$end_date = $_POST["end_date"]; 

#endregion

#region - where to show errors
if($cmd == "history_get_count" 
|| $cmd == "history_get_table" 
|| $cmd == "context_json"
|| $cmd == "test_nodes"){

}else{}
    ini_set ('display_errors', 'on');
    ini_set ('log_errors', 'on');
    ini_set ('display_startup_errors', 'on');
    ini_set ('error_reporting', E_ALL);


#endregion

#region - book xpath variables
    $json_data_xpath = "/html/head/script[@type='application/ld+json']";

    $author_xpath = '//*[@id="__next"]/div/main/div[1]/div[2]/div[1]/div[2]/div[8]/div[2]/div/div[1]/div[2]/div[1]/div[1]/h4/a/span';
    //$author_id_xpath = '';
    $title_xpath = '//*[@id="__next"]/div/main/div[1]/div[2]/div[1]/div[1]/div[1]/h1';
    $description_xpath = '//*[@id="__next"]/div/main/div[1]/div[2]/div[1]/div[2]/div[4]/div/div[1]/div/div/span/text()';
    $rating_xpath = '//*[@id="__next"]/div/main/div[1]/div[2]/div[1]/div[2]/div[2]/a/div[1]/div';
    $rating_num_xpath = '//*[@id="__next"]/div[2]/main/div[1]/div[2]/div[1]/div[2]/div[2]/a/div[2]/div/span[1]';
    $review_num_xpath = '//*[@id="__next"]/div[2]/main/div[1]/div[2]/div[1]/div[2]/div[2]/a/div[2]/div/span[2]';
    $genres_xpath = '//*[@id="__next"]/div/main/div[1]/div[2]/div[1]/div[2]/div[5]/ul';
    
    $pages_xpath = '//*[@id="__next"]/div/main/div[1]/div[2]/div[1]/div[2]/div[6]/div/span[1]/span/div/p[1]';
    $published_xpath = '//*[@id="__next"]/div/main/div[1]/div[2]/div[1]/div[2]/div[6]/div/span[1]/span/div/p[2]';
    $author_desc_xpath = '//*[@id="__next"]/div/main/div[1]/div[2]/div[1]/div[2]/div[8]/div[3]/div[1]/div/div/span';
    $image_file_xpath = '';
    $image_path_xpath = '//*[@id="__next"]/div/main/div[1]/div[1]/div/div[1]/div/div/div/div/div/div/img';
    $isbn10_xpath = '';
    $isbn13_xpath = '//*[@id="__next"]/div/main/div[1]/div[2]/div[1]/div[2]/div[6]/div/span[2]/div[1]/span/div/dl/div[3]/dd/div/div[1]';
#endregion

#region - author xpath variables
    $author_link_xpath = '//a[@class="ContributorLink"]/@href';   
    $author_name_xpath = '/html/body/div[2]/div[3]/div[1]/div[2]/div[3]/div[2]/div[2]/h1/span'; 
    $born_when_xpath = '/html/body/div[2]/div[3]/div[1]/div[2]/div[3]/div[2]/div[4]'; 
    $born_where_xpath = '/html/body/div[2]/div[3]/div[1]/div[2]/div[3]/div[2]/text()[1]'; 
    $genres_authors_xpath = '/html/body/div[2]/div[3]/div[1]/div[2]/div[3]/div[2]/div[10]'; 
    $about_authors_xpath = '/html/body/div[2]/div[3]/div[1]/div[2]/div[3]/div[2]/div[13]'; 
    $image_path_authors_xpath = '/html/body/div[2]/div[3]/div[1]/div[2]/div[3]/div[1]/a[1]/img/@src'; 
    $influences_authors_xpath = '/html/body/div[2]/div[3]/div[1]/div[2]/div[3]/div[2]/div[12]'; 
#endregion

#region - functions
function test_nodes($dom, $xpath){
    $valid_xpath_1 = "";
	$data = "";
	
	//$dom = hopToNext($url, '', true);
	$data = searchPageDOM($dom, $xpath);
	usleep(1000);

	if(strlen($data) < 1)
	{
		$valid_xpath_1 = "<span style='color:red;'><b>false</b></span>";
	}else{
		$valid_xpath_1 = "<span style='color:green;'><b>true</b></span>";
	}
	
    echo $valid_xpath_1;
}

// parses links like  https://www.goodreads.com/list/tag/romance
function parseBookListPage($link){
    $l = $link;
    $full_arr = [];

    $side_1 = hopToNextAll($l, '/html/body/div[2]/div[3]/div[1]/div[1]/div[2]/div[1]/div[*]/div[1]/a/@href');
    usleep(1000);

    $side_2 = hopToNextAll($l, '/html/body/div[2]/div[3]/div[1]/div[1]/div[2]/div[1]/div[*]/div[2]/a/@href');
    usleep(1000);

    //$full_arr = array_merge($side_1, $side_2);

    //counter and lists
    $ic1 = 0;
    $ic2 = 0;
    $link_list = "";

    foreach ($side_1 as $i) {

        $dataz = $side_1->item($ic1)->nodeValue;
        $link_list .= $dataz. ',';

        $ic1++;
        usleep(1000);
    }

    foreach ($side_2 as $i) {

        $dataz = $side_2->item($ic2)->nodeValue;
        $link_list .= $dataz. ',';

        $ic2++;
        usleep(1000);
    }

    return $link_list;
}

//parses links like https://www.goodreads.com/list/show/1333.Young_Adult_Romance
function parseListOfBookPage($link){

    $l = $link;

    $side_1 = hopToNextAll($l, '//*[@id="all_votes"]/table/tbody/tr[*]/td[3]/a/@href');
    usleep(1000);

    //counter and lists
    $ic1 = 0;
    $link_list = "";

    foreach ($side_1 as $i) {

        $dataz = $side_1->item($ic1)->nodeValue;
        $link_list .= $dataz. ',<br /> ';

        $ic1++;
        usleep(1000);
    }

    return $link_list;
}

function runScoutParser($link){
    $domain = 'https://www.goodreads.com';

    $bookLinkList = parseBookListPage($link);
    $book_arr = explode(',', $bookLinkList);

    //First outter loop - for book list links
    //Parsed, sample data = /list/show/10762,/list/show/12362
    for ($i = 0, $j = count($book_arr); $i <= $j; $i++) 
	{
        $full_list_show_link = $domain.$book_arr[$i];
        echo $full_list_show_link.':<br /><br />'; 

        usleep(2000);

        //amount of pages to go through
        $min_page_in_list = 1;
        $max_page_in_list = 100;

        //Second outter loop - books listed in page
        //iterate through each page and get book links
        for ($l = $min_page_in_list, $m = $max_page_in_list; $l <= $m; $l++) 
	    {
            $newLinkPageWithNum = $full_list_show_link.'?page='.$l;
            echo $newLinkPageWithNum.': <br /><br />'; 

            $domDoc = returnedPage($newLinkPageWithNum, 'true'); // returns only dom doc
            usleep(2000);

            $domXPath = new DOMXPath($domDoc);	//here we use the dom doc, to get dom xp

            $nodes = $domXPath->query('//a[@class="bookTitle"]/@href');

            $i = 0;
            for($x = 0; $x <= 100; $x++) {
                $datazz = $nodes->item($x)->nodeValue;

                $check = fetchQuery("SELECT source_link FROM book_data_scout WHERE source_link = '".$datazz."' ");
                usleep(1000);

                $rows = mysqli_fetch_array($check);
    

                if(!$rows){
                    
                    //Import links into database
                    execQuery('INSERT INTO book_data_scout(source_id, source_link, processed_links, book_grabber_processed, created_at) VALUES ("'.clearRawLinks($datazz).'", "'.$datazz.'", "", "false", NOW());');
                }else{
                    //do nothing, since this already exists in the database
                }

                echo $datazz;
                usleep(100);
            }

        }

    }

}

function clearRawLinks($rawLink, $isAuthor=false){

    if($isAuthor == false){
        $data = '';
        $trimmed = str_replace('/book/show/', '', $rawLink);
        $str_arr = [];

        if(is_numeric($trimmed)){
            return $trimmed;
        }else{
            if (strpos( $trimmed, '.' ) !== false){
                $str_arr = explode(".", $trimmed);
            }else if(strpos( $trimmed, '-' ) !== false){
                $str_arr = explode("-", $trimmed);
            }
        
            $data = $str_arr[0];
            
            return  $data;
        }
    }else if($isAuthor == true){
        $data = '';
        $trimmed = str_replace('https://www.goodreads.com/author/show/', '', $rawLink);
        $str_arr = [];

        if(is_numeric($trimmed)){
            return $trimmed;
        }else{
            if (strpos( $trimmed, '.' ) !== false){
                $str_arr = explode(".", $trimmed);
            }else if(strpos( $trimmed, '-' ) !== false){
                $str_arr = explode("-", $trimmed);
            }
        
            $data = $str_arr[0];
            
            return  $data;
        }
    }

    

    
}

function importBookingAndAuthorData($bookImportID){}

function cleanRawLinkOperation(){
    
    $getListOfMissingIds = fetchQuery('SELECT GROUP_CONCAT(id ORDER BY source_id ASC SEPARATOR ",") as outputs FROM book_data_scout WHERE source_id = "";');
    $rows = mysqli_fetch_array($getListOfMissingIds);
    $getListOfMissingIds_row = $rows['outputs'];

    $blankSourceId_arr = explode(',', $getListOfMissingIds_row);

    for ($i = 0, $j = count($blankSourceId_arr); $i <= $j; $i++) {

        $sourceLink = fetchQuery('SELECT source_link FROM book_data_scout WHERE id = "'.$blankSourceId_arr[$i].'";');
        $rows2 = mysqli_fetch_array($sourceLink);
        $sourceLink_row = $rows2['source_link'];

        echo '1111 ; '.$blankSourceId_arr[$i].' ~~ '.$sourceLink_row.'<br />';

        $cleaned_source_link = clearRawLinks($sourceLink_row);

        echo '2222 ; '.$blankSourceId_arr[$i].' ~~ '.$cleaned_source_link.'<br />';

        execQuery('UPDATE book_data_scout SET source_id = "'.$cleaned_source_link.'"  WHERE id = "'.$blankSourceId_arr[$i].'";');
    }


}

#endregion
 
if($cmd == "test_nodes"){

    $test_url = 'https://www.goodreads.com/book/show/929';

    $dom = hopToNext($test_url, '', true);

    usleep(1000);

    $json_data_xpath_test = test_nodes($dom, $json_data_xpath);
    $author_xpath_test = test_nodes($dom, $author_xpath);
    //$author_id_xpath_test = '';
    $title_xpath_test = test_nodes($dom, $title_xpath);
    $description_xpath_test = test_nodes($dom, $description_xpath);
    $rating_xpath_test = test_nodes($dom, $rating_xpath);

    $rating_num_xpath_test = test_nodes($dom, $rating_num_xpath);
    $review_num_xpath_test = test_nodes($dom, $review_num_xpath);

    $genres_xpath_test = test_nodes($dom, $genres_xpath);
    $pages_xpath_test = test_nodes($dom, $pages_xpath);
    $published_xpath_test = test_nodes($dom, $published_xpath);
    $author_desc_xpath_test = test_nodes($dom, $author_desc_xpath);
    //$image_file_xpath_test = searchPageDOM($dom, $image_file_xpath);
    $image_path_xpath_test = test_nodes($dom, $image_path_xpath);
    //$isbn10_xpath_test = '';
    $isbn13_xpath_test = test_nodes($dom, $isbn13_xpath);
    //$created_at_xpath_test = '';

    $data_return = "<table class='table'>
                        <thead>
                            <tr>
                                <th>Node</th>
                                <th>Pass or Fail</th>
                            </tr>
                        </thead>
                        <tbody >
                            <tr>
                                <td>".'Script Json'."</td> 
                                <td>".$json_data_xpath_test."</td> 
                            </tr>
                            <tr>
                                <td>".'author_xpath_test'."</td> 
                                <td>".$author_xpath_test."</td> 
                            </tr>
                         
                            <tr>
                                <td>".'title_xpath_test'."</td> 
                                <td>".$title_xpath_test."</td> 
                            </tr>
                            <tr>
                                <td>".'description_xpath_test'."</td> 
                                <td>".$description_xpath_test."</td> 
                            </tr>
                            <tr>
                                <td>".'rating_xpath_test'."</td> 
                                <td>".$rating_xpath_test."</td> 
                            </tr>
                            <tr>
                                <td>".'genres_xpath_test'."</td> 
                                <td>".$genres_xpath_test."</td> 
                            </tr>
                            <tr>
                                <td>".'pages_xpath_test'."</td> 
                                <td>".$pages_xpath_test."</td> 
                            </tr>
                            <tr>
                                <td>".'published_xpath_test'."</td> 
                                <td>".$published_xpath_test."</td> 
                            </tr>
                            <tr>
                                <td>".'author_desc_xpath_test'."</td> 
                                <td>".$author_desc_xpath_test."</td> 
                            </tr>
                            <tr>
                                <td>".'image_file_xpath_test'."</td> 
                                <td>".$image_file_xpath_test."</td> 
                            </tr>
                            <tr>
                                <td>".'image_path_xpath_test'."</td> 
                                <td>".$image_path_xpath_test."</td> 
                            </tr>
                         
                            <tr>
                                <td>".'isbn13_xpath_test'."</td> 
                                <td>".$isbn13_xpath_test."</td> 
                            </tr>
                         
                            
                            
                        </tbody>
                        
                    </table>";

    echo $data_return;


}else if($cmd == "run_import"){
    if($jobType == "book_type"){ // gets all bookgs indiscriminately 

        //
        usleep(3500);

        $origin_link = 'https://www.goodreads.com/book/show/'.$id;
        $dom = hopToNext($origin_link, '', true);

        usleep(1500);

        $xpath_json = "/html/head/script[@type='application/ld+json']";
        $dom_json = searchPageDOM($dom, $xpath_json);
        $jArr = json_decode($dom_json, true);



        usleep(3500);

        $author_value = searchPageDOM($dom, $author_xpath);
        $author_id_value = $jArr['author']['url'];
        $title_value = searchPageDOM($dom, $title_xpath);
        $description_value = searchPageDOM($dom, $description_xpath);
        $rating_value = searchPageDOM($dom, $rating_xpath);
        $genres_value = searchPageDOM($dom, $genres_xpath);
        $pages_value = $jArr['numberOfPages'];
        $published_value = searchPageDOM($dom, $published_xpath);
        $author_desc_value = searchPageDOM($dom, $author_desc_xpath);
        //$image_file_value = searchPageDOM($dom, $image_file_xpath);
        $image_path_value = $jArr['image'];
        //$isbn10_value = searchPageDOM($dom, $isbn10_xpath);
        $isbn13_value = $jArr['isbn'];

        //$created_at_value = '';

        //Import to Database

        if(strlen($author_value) < 1){
            //do nothing
        }else{
            execQuery("INSERT INTO book_data (source_id, author, title, description,genres, pages,rating, people_who_rated_num, published, image_path, isbn10, isbn13, created_at) VALUES ( '".$id."', '".$author_value."', '".$title_value."', '".$description_value."', '".$genres_value."', '".$pages_value."', '".$rating_value."', '".$zzzzzzz."', '".$published_value."', '".$image_path_value."', 'null', '".$isbn13_value."', NOW());");
        }

        echo $id.' - '.$author_value.' - '.$title_value.' - '.$description_value.' - '.$image_path_value;

        //echo $id.' - '.$author_value.' - '.$title_value.' - '.$description_value.' - '.$rating_value.' - '.$genres_value.' - '.$pages_value.' - '.$published_value.' - '.$author_desc_value.' - '.$image_path_value;

        usleep(1500);

    }else if($jobType == "specific_type"){ // gets bookgs and authors based on scout data
        
        #region DOM Pull
        //sleep
        usleep(3500);

        echo 'ID GOES HERE (NON DB): '.$id;

        

        //see if the book already exists before we even do anything
        

        $db_id = fetchQuery('SELECT source_id FROM book_data_scout WHERE id = '.$id.'; ');
        $rowz = mysqli_fetch_array($db_id);

        $test2 = fetchQuery('SELECT source_id FROM book_data WHERE source_id = "'.$rowz['source_id'].'"; ');
        $rows2 = mysqli_fetch_array($test2); 

        if(!$rows2){    
            echo ' ~ ID GOES HERE: '.$rowz['source_id'];

            $origin_link = 'https://www.goodreads.com/book/show/'.trim($rowz['source_id']);
            $dom = hopToNext($origin_link, '', true);
            usleep(3500);

            $auth_link_xpath = '//*[@id="__next"]/div[2]/main/div[1]/div[2]/div[1]/div[2]/div[8]/div[2]/div/div[1]/div[2]/div[1]/div[1]/h4/a/@href';
            $auth_link = searchPageDOM($dom, $auth_link_xpath);

            echo ' ~ AUTHOR LINK: '.clearRawLinks($auth_link, true);

            $auth_dom = hopToNext($auth_link, '', true);

            //sleep
            usleep(3500);

            $xpath_json = "/html/head/script[@type='application/ld+json']";
            $dom_json = searchPageDOM($dom, $xpath_json);
            $jArr = json_decode($dom_json, true);

            //sleep
            usleep(3500);
            #endregion

            #region Book DOM Search

            $author_value = searchPageDOM($dom, $author_xpath);
            $author_id_value = $jArr['author']['url'];
            $title_value = searchPageDOM($dom, $title_xpath);
            $description_value = searchPageDOM($dom, $description_xpath);
            $rating_value = searchPageDOM($dom, $rating_xpath);
            $genres_value = searchPageDOM($dom, $genres_xpath);

            $rating_num_value = searchPageDOM($dom, $rating_num_xpath);
            $review_num_value = searchPageDOM($dom, $review_num_xpath);

            $pages_value = $jArr['numberOfPages'];
            $published_value = searchPageDOM($dom, $published_xpath);
            $author_desc_value = searchPageDOM($dom, $author_desc_xpath);
            //$image_file_value = searchPageDOM($dom, $image_file_xpath);
            $image_path_value = $jArr['image'];
            //$isbn10_value = searchPageDOM($dom, $isbn10_xpath);
            $isbn13_value = $jArr['isbn'];

            //$created_at_value = '';
            #endregion

            #region Author DOM Search
            $author_link_value = searchPageDOM($auth_dom, $author_link_xpath);
            $author_name_value = searchPageDOM($auth_dom, $author_name_xpath);
            $born_when_value = searchPageDOM($auth_dom, $born_when_xpath);
            $born_where_value = searchPageDOM($auth_dom, $born_where_xpath);
            $genres_authors_value = searchPageDOM($auth_dom, $genres_authors_xpath);
            $about_authors_value = searchPageDOM($auth_dom, $about_authors_xpath);
            $image_path_authors_value = searchPageDOM($auth_dom, $image_path_authors_xpath);
            $influences_authors_value = searchPageDOM($auth_dom, $influences_authors_xpath);

            echo '  ~~~~ this is AUTHOR data ~~~~  '.$author_link_value.' - '.$born_when_value.' - '.$born_where_value.' - '.$genres_authors_value.' - '.$about_authors_value.' - '.$image_path_authors_value;

            #endregion

            #region DOM Import
            //Import to Database
            if(strlen($author_value) < 1){
                //do nothing
            }else{

                

                
                //Insert book
                execQuery("INSERT INTO book_data (source_id, author, author_id, title, description,genres, pages,rating, people_who_rated_num, people_who_reviewed_num, published, image_path, isbn10, isbn13, created_at) VALUES ( '".$rowz['source_id']."', '".$author_value."', '".clearRawLinks($auth_link, true)."', '".$title_value."', '".$description_value."', '".$genres_value."', '".$pages_value."', '".$rating_value."', '".$rating_num_value."', '".$review_num_value."', '".$published_value."', '".$image_path_value."', 'null', '".$isbn13_value."', NOW());");
                
                

                //Insert Author, but only if they don't exist
                $test = fetchQuery('SELECT author_source_id FROM book_author_data WHERE author_source_id = "'.clearRawLinks($auth_link, true).'"; ');
                $rows = mysqli_fetch_array($test);

                if(!$rows){
                    execQuery("INSERT INTO book_author_data(author_source_id, author_name, born_where, born_when, died_when, genres, influences, about, image_path, created_at) VALUES ('".clearRawLinks($auth_link, true)."', '".$author_name_value."', '".$born_where_value."', '".trim($born_when_value)."', 'null', '".$genres_authors_value."', 'null', '".$about_authors_value."', '".$image_path_authors_value."', NOW());");
                
                }else{
                    echo 'record was found...which is what we do not want unforunately...or fortunately lol';
                }
            }
    }else{
        echo 'record was found...which is what we do not want unforunately...or fortunately lol';
    }

        #endregion

        #region DOM Test/Result
        //echo $id.' - '.$author_value.' - '.$title_value.' - '.$description_value.' - '.$image_path_value;

        echo '  ~~~~ this is BOOK data ~~~~  '.$id.' - '.$author_value.' - '.$title_value.' - '.$description_value.' - '.$rating_value.' - '.$rating_num_value.' - '.$review_num_value.' - '.$genres_value.' - '.$pages_value.' - '.$published_value.' - '.$author_desc_value.' - '.$image_path_value;

        usleep(1500);
        #endregion
    }else if($jobType == "author_img_type"){ // gets author images

        //get id from client
        $db_id = fetchQuery('SELECT * FROM book_author_data WHERE id = '.$id.'; ');
        $rowz = mysqli_fetch_array($db_id);

        if(!$rowz){  //if row not found

            //do nothing
            echo 'didnt find nothinbg';
        }else{
            $path = __DIR__ .'/img_repo/authors/'.$rowz['author_source_id'].'/';
            mkdir($path, 0777, true);
            copy($rowz['image_path'], $path.'img.jpg');

            echo 'sucessful copy '. $rowz['image_path'];
            echo '<br /> path '. $path;
        }
    
    }else if($jobType == "book_img_type"){ //gets book images

        //get id from client
        $db_id = fetchQuery('SELECT * FROM book_data WHERE id = '.$id.'; ');
        $rowz = mysqli_fetch_array($db_id);

        if(!$rowz){  //if row not found

            //do nothing
            echo 'didnt find nothinbg';
        }else{
            $path = __DIR__ .'/img_repo/books/'.$rowz['source_id'].'/';
            mkdir($path, 0777, true);
            copy($rowz['image_path'], $path.'img.jpg');

            echo 'sucessful copy '. $rowz['image_path'];
            echo '<br /> path '. $path;
        }
    }
}else if($cmd == "history_update"){

    execQuery("INSERT INTO book_import_history (start, end, created_at) VALUES ( '".$start_date."', '".$end_date."', NOW());");

}else if($cmd == "history_get_table"){

    $return = fetchQuery("SELECT * FROM book_import_history ORDER BY id DESC;");
    //$rows = mysqli_fetch_assoc($return);

    $data = "";
    while($row = mysqli_fetch_array($return))
    {
        $start = $row['start'];
        $end = $row['end'];

        $import_count = $end - $start;

        $data .= "
            <tr>
                <td>".$row['id']."</td>
                <td>".$row['start']."</td>
                <td>".$row['end']."</td>
                <td>".$import_count."</td>
                <td>".$row['created_at']."</td>
            </tr>
        ";
    }
 
    echo $data;

}else if($cmd == "history_get_count"){
    $return = fetchQuery("SELECT start, end FROM book_import_history ORDER BY created_at DESC LIMIT 1;");
    $rows = mysqli_fetch_array($return);


    echo $rows['start'].'|'.$rows['end'];
}else if($cmd == "context_json"){
 
	$valid_xpath_1 = "";
	$xpath = "/html/head/script[@type='application/ld+json']";
	$data = "";
	
	$dom = hopToNext('https://www.goodreads.com/book/show/929', '', true);
	$data = searchPageDOM($dom, $xpath);
	
	if(strlen($data) < 1)
	{
		$valid_xpath_1 = "false";
	}else{
		$valid_xpath_1 = "true";
	}

    $jArr = json_decode($data, true);

    echo $jArr['name'].'<br />';
	
    echo $valid_xpath_1 . ' - ' . $data;
}else if($cmd == "scout_1"){

    /*
        - Order of Operations for Scout -
        1. Go to https://www.goodreads.com/list/tag/romance'
        2. This link provides lists of good books, can be easily obtained and fed to the scouter
        3. Go to the first link in the 'list/tag', which should be a link like https://www.goodreads.com/list/show/1333.Young_Adult_Romance?page=2
        4. There should be 100 books per page
        5. We need to iterate through each page and get the list of 100 books; we should only be grabing this page **ONE TIME** and scrape any thing we can off it...while each list gives thousands of pages of books...the ui only gives you the first 100 pages by default...which is enough for our tastes
        6. We need a way for the scout to know not do grab duplicate books
        7. We need to know how many people vote for ratings...this is a requirement to know if ratings are accurate...instead of just 1 person voting 5 starts...and the book is actually crap, which there is a lot of on this site
        7.a. We require the 5 star ratings to be accurate, as ai processing is limited...so we know which books are good to render by the ai and not wasting time/energy/money on garbage books no one reads or cares about
        8. We need to clean the url links once gathered (a function can go through the list and clean this up...there don't appear to be too many pattern...any outliers can be further assessed and processed)
        9. tracking progress may not be possible; just run quereies on the database
        10. Need to know when we run out of pages...as not all books go to 100 pages
        11. AFTER ALLLL This is done; reconfigure book grabber to pull books generated from the scouts
        12. Some additional fields for the scout; 
            raw_links:varchar:1000, 
            processed_links:varchar:1000, 
            book_grabber_processed:bool
        13. Some additional fields for the book grabber (DO THIS FIRST); 
            people_who_rated_num:varchar:20 //strip commas
        14. Need to gather authors in a table; we need to find a way not to have duplicates... this can also be done in the scouting phase


    */

    runScoutParser('https://www.goodreads.com/list/tag/graphic-novels');

    $test = fetchQuery('SELECT source_link FROM book_data_scout WHERE source_link = "ddd"; ');
    $rows = mysqli_fetch_array($test);

    if(!$rows){
       echo 'error';
    }else{
        echo $rows['source_link'];
    }

    //print( $test);


}else if($cmd == "scout_2"){

    // echo '<br />';
    // echo clearRawLinks('/book/show/18135.Romeo_and_Juliet');
    // echo '<br />';
    // echo clearRawLinks('/book/show/12900174-the-vincent-boys');
    // echo '<br />';
    // echo clearRawLinks('/book/show/12900174-the-vincent-boys');

    //cleanRawLinkOperation();




}else if($cmd == "scout_3"){

    // echo '<br />';
    // echo clearRawLinks('/book/show/18135.Romeo_and_Juliet');
    // echo '<br />';
    // echo clearRawLinks('/book/show/12900174-the-vincent-boys');
    // echo '<br />';
    // echo clearRawLinks('/book/show/12900174-the-vincent-boys');

    //cleanRawLinkOperation();




}else if($cmd == "generate_book_summary"){

    echo sendAIRequest('who is elon musk?');

}else if($cmd == "flat_icon"){

    usleep(1500);

        $save_img_path = $id;
        $origin_link = $jobType;
        $dom = hopToNext($origin_link, '', true);

        usleep(1500);

        $xpath_json = '//*[@id="detail"]/div/div[1]/section/div/div/div[2]/div/div/img/@src';
        $dom_json = searchPageDOM($dom, $xpath_json);

        //----------------------------

        $image_link = $dom_json;//Direct link to image
        $split_image = pathinfo($image_link);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL , $image_link);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $response= curl_exec ($ch);
        curl_close($ch);
        $file_name = $save_img_path."\\".$split_image['filename'].".".$split_image['extension'];
        $file = fopen($file_name , 'w') or die("X_x");
        fwrite($file, $response);
        fclose($file);

        echo $dom_json;

}

?>