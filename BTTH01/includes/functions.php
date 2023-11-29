<?php
function pdo(PDO $pdo, string $sql, array $arguments = null)
{
    if (!$arguments) {                  
        return $pdo->query($sql);        
    }
    $statement = $pdo->prepare($sql);    
    $statement->execute($arguments);     
    return $statement;                  
}

function html_escape($text): string
{
    $text = $text ?? ''; 

    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8', false); 
}

function redirect(string $location, array $parameters = [], $response_code = 302)
{
    $qs = $parameters ? '?' . http_build_query($parameters) : ''; 
    $location = $location . $qs;                                  
    header('Location: ' . $location, true, $response_code);        
    exit;                                                       
}

function create_filename(string $filename, string $uploads): string
{
    $basename  = pathinfo($filename, PATHINFO_FILENAME);        
    $extension = pathinfo($filename, PATHINFO_EXTENSION);        
    $cleanname = preg_replace("/[^A-z0-9]/", "-", $basename);     
    $filename  = $cleanname . '.' . $extension;                
    $i         = 0;                                            
    while (file_exists($uploads . $filename)) {               
        $i        = $i + 1;                                      
        $filename = $basename . $i . '.' . $extension;           
    }
    return $filename;                                       
}