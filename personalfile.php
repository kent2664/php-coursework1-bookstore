<?php
    define("SRC_DIR","./UserDirs/");
    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":
            if(basename($_SERVER["PATH_INFO"])==="read"){
                if(isset($_REQUEST["uname"])){
                    if(is_dir(SRC_DIR.$_REQUEST["uname"])){
                        $dirContent = array_diff(scandir(SRC_DIR.$_REQUEST["uname"]),["..","."]);
                        $output = ["dirname"=>$_REQUEST["uname"],"contents"=>$dirContent];
                        echo json_encode($output);
                    }else{
                        httpResponseHandler("Directory ".$_REQUEST["uname"]." does not exist.",404);
                    }
                }else{
                    httpResponseHandler("uname key does not exists!!!",406);    
                }
            }else{
                httpResponseHandler("Very bad request :(",400);
            }
            break;
        case "POST":
            switch(basename($_SERVER["PATH_INFO"])){
                case "read":
                    if(isset($_REQUEST["uname"]) && isset($_REQUEST["filename"])){
                        $dstFile = SRC_DIR.$_REQUEST["uname"]."/".$_REQUEST["filename"];
                        if(file_exists($dstFile)){
                            $content = file_get_contents($dstFile);
                            $output = ["dir"=>$dstFile,"content"=>$content];
                            echo json_encode($output);
                        }else{
                            httpResponseHandler("Content does not exist.",404);    
                        }
                    }else{
                        httpResponseHandler("Shameful bad request :(",400);
                    }
                    break;
                case "createdir":
                        if(isset($_REQUEST["uname"])){
                            if(!is_dir(SRC_DIR.$_REQUEST["uname"])){
                                if(mkdir(SRC_DIR.$_REQUEST["uname"]))
                                    httpResponseHandler("Directory ".$_REQUEST["uname"]." created.",201);
                                else
                                    httpResponseHandler("Directory ".$_REQUEST["uname"]." not created.",500);
                            }else{
                                httpResponseHandler("Directory ".$_REQUEST["uname"]." already exists.",403);
                            }
                        }else{
                            httpResponseHandler("uname key does not exists!!!",406);
                        }
                    break;
                default:
                    httpResponseHandler("Very bad request :(",400);
            }
            break;
        case "PUT":
                if(basename($_SERVER["PATH_INFO"]) === "writefile"){
                    $rawData = file_get_contents("php://input");
                    $data = json_decode($rawData);
                    if(is_dir(SRC_DIR.$data->uname)){
                        $readDir = SRC_DIR.$data->uname."/";
                        if(!file_exists($readDir.$data->filename)){
                            $file = fopen($readDir.$data->filename,"w");
                            fwrite($file,$data->content);
                            fclose($file);
                            httpResponseHandler("File ".$data->filename." created.",201);
                        }else{
                            httpResponseHandler("File ".$data->filename." already exists.",403);
                        }
                    }else{
                        httpResponseHandler("Directory ".$data->uname." does not exists.",403);
                    }
                }else{
                    httpResponseHandler("Very bad request :(",400);
                }
            break;
        default:
            httpResponseHandler("Very bad request :(",400);
    }
    function httpResponseHandler(string $resBody,int $resCode):void{
        http_response_code($resCode);
        echo $resBody;
    }
?>