<?php

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
            $books[$idx]["price"] = $books[$idx]["price"] * 0.9;
        }
    }

    
}

//question 2
applyDiscounts($books);

//print_r($books);
echo '<link rel="stylesheet" href="style.css">';
define("SRC_DIR","./");
    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":
            switch(basename($_SERVER["PATH_INFO"])){
                case "booklist":
                    echo '<link rel="stylesheet" href="style.css">';
                    echo "<h1>This is book list</h1>";
                    echo '<main>';
                    echo '<table class="table-container" style="border: 1px solid black;">';
                    echo '<thead style="border: 1px solid black;">';
                    echo '<th style="border: 1px solid black;">';
                    echo 'Title';
                    echo '</th>';
                    echo '<th style="border: 1px solid black;">';
                    echo 'Author';
                    echo '</th>';
                    echo '<th style="border: 1px solid black;">';
                    echo 'Genre';
                    echo '</th>';
                    echo '<th style="border: 1px solid black;">';
                    echo 'Original / Discounted price';
                    echo '</th>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach($books as $book){
                        echo "<tr>";
                             echo "<td>".$book["title"]."</td>";
                             echo "<td>".$book["author"]."</td>";
                             
                             echo "<td>".$book["genre"]."</td>";
                             echo "<td>".($book["genre"] === "Science Fiction" ?"discounted:". $book["price"] * 0.9 . "original :". $book["price"]:$book["price"])."</td>";
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
            // echo $_SERVER["PATH_INFO"];
            switch(basename($_SERVER["PATH_INFO"])){
                case "input":
                    //check parameters
                    if(isset($_REQUEST["title"]) && isset($_REQUEST["author"]) && isset($_REQUEST["genre"]) && isset($_REQUEST["price"])){
                        echo $_REQUEST["title"];
                        $title = $_REQUEST["title"];
                        $author = $_REQUEST["author"];
                        $genre = $_REQUEST["genre"];
                        $price = $_REQUEST["price"];

                        $addBook = [
                                   "title" => $title,
                                   "author" => $author,
                                   "genre" => $genre,
                                   "price" => $price];

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
                            $file = fopen($readDir."bookstore_log.txt","w");
                            fwrite($file,"[".date("Y-m-d h:i:s"."]"));
                            fwrite($file,"| Book title: ".$title."|");
                            fwrite($file,"| IP address: ".$_SERVER['REMOTE_ADDR']."|");
                            fwrite($file,"| User agent: ".$_SERVER['HTTP_USER_AGENT']."|");
                            fclose($file);
                                // httpResponseHandler("File ".$data->filename." created.",201);
                        }else{
                            echo "THIS IS WHAT???";
                        }
  
                    } else {
                        echo "input data is not correct";
                    }
                break;
            }
            break;

    }
?>