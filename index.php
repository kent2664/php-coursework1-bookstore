<?php

//book inventory
$books = [
    [
        'title' => 'Dune',
        'author' => 'Frank Herbert',
        'genre' => 'Science Fiction',
        'price' => 29.99
    ],
    [
        'title' => 'Harry Poter',
        'author' => 'J.K.roling',
        'genre' => 'Fantasy',
        'price' => 25.99
    ],
    [
        'title' => 'sample1',
        'author' => 'John Doe',
        'genre' => 'documentary',
        'price' => 20.00
    ]
];


function applyDiscounts(array &$books) {
    // for loop for discounting the books
    for($idx = 0; $idx < sizeof($books); $idx++){
        if($books[$idx]["genre"] === "Science Fiction"){
            //If it science fiction , it should be discounted
            $books[$idx]["price"] = round($books[$idx]["price"] * 0.9,2);
        }
    }

    
}

//question 2
applyDiscounts($books);


echo '<link rel="stylesheet" href="style.css">';
define("SRC_DIR","./");
    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":
            //check the APi is booklist
            switch(basename($_SERVER["PATH_INFO"])){
                case "booklist":
                    echo '<link rel="stylesheet" href="/phpnov1/php-coursework1-bookstore/style.css">';
                    echo '<h1 style="text-align:center;">This is book list</h1>';
                    echo '<main class="table-container back">';
                    echo '<table>';
                    echo '<thead>';
                    echo '<th>';
                    echo 'Title';
                    echo '</th>';
                    echo '<th>';
                    echo 'Author';
                    echo '</th>';
                    echo '<th>';
                    echo 'Genre';
                    echo '</th>';
                    echo '<th>';
                    echo 'Original / Discounted price';
                    echo '</th>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach($books as $book){
                        //information of books
                        echo "<tr>";
                             echo "<td>".$book["title"]."</td>";
                             echo "<td>".$book["author"]."</td>";
                             
                             echo "<td>".$book["genre"]."</td>";
                             echo "<td>".($book["genre"] === "Science Fiction" ?"discounted:". $book["price"]. "&nbsp;original :". round($book["price"] / 0.9,2) :$book["price"])."</td>";
                            echo "</tr>";
                    }
                   
                    echo '</tbody>';
                    echo '</table>';
                    echo 'Request time: ('.  date("Y-m-d h:i:s"). ')<br>';
                    echo 'IP address ('. $_SERVER['REMOTE_ADDR'] . ')<br>';
                    echo 'User agent ('. $_SERVER['HTTP_USER_AGENT'] . ')<br>';
                    echo '</main>';
                    break;
            }
            break;
        case "POST":
            // when the form submit, this case will be executed.
            switch(basename($_SERVER["PATH_INFO"])){
                case "input":
                    //check parameters
                    if(isset($_REQUEST["title"]) && isset($_REQUEST["author"]) && isset($_REQUEST["genre"]) && isset($_REQUEST["price"])){
                        echo "Added :".$_REQUEST["title"]."<br>";
                        $title = $_REQUEST["title"];
                        $author = $_REQUEST["author"];
                        $genre = $_REQUEST["genre"];
                        $price = $_REQUEST["price"];

                        //make a book object
                        $addBook = [
                                   "title" => $title,
                                   "author" => $author,
                                   "genre" => $genre,
                                   "price" => $price];

                        //add the object to book list
                        $books[] = $addBook;

                        //print_r($books);
                        applyDiscounts($books);
                        $totalPrice = 0;
                        foreach($books as $book){
                            $totalPrice += $book["price"];
                        }
                         
                        echo "Total price after discounts :$$totalPrice";

                        //writing log 
                        if(is_dir(SRC_DIR)){
                            $readDir = SRC_DIR;
                            //to avoid overwriting the log, use "append" mode.
                            $file = fopen($readDir."bookstore_log.txt","a");
                            fwrite($file,"[".date("Y-m-d h:i:s"."]"));
                            fwrite($file,"| Book title: ".$title."|");
                            fwrite($file,"| IP address: ".$_SERVER['REMOTE_ADDR']."|");
                            fwrite($file,"| User agent: ".$_SERVER['HTTP_USER_AGENT']."| \r\n");
                            fclose($file);

                        }else{
                            echo "Directory error";
                            http_response_code(404);
                        }
  
                    } else {
                        echo "input data is not correct";
                        http_response_code(403);
                    }
                break;
            }
            break;

    }
?>