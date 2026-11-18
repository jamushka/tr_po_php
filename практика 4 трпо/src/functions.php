<?php
function loadEmployees($filePath){
    $rows = [];
    $headers = [];

    if (($handle = fopen($filePath, 'r'))!==false){
        $isFirstRow=true;

        while (($row = fgetcsv($handle, 1000, ';')) !== false){
            if ($isFirstRow == true){
                $headers = $row;
                $isFirstRow = false;

            } 
            else{
                $rows[] = $row;

            }
        }
        fclose($handle);
    }
    return $rows;
}

function mapEmployeeRows($rows) {
    $mappedRows = [];
    foreach ($rows as $row) {
        $mappedRows[] = ['id' => $row[0], 'name' => $row[1], 'department' => $row[2], 'experience' => $row[3], 'email' => $row[4]];
    }
    return $mappedRows;
}



function e($text) {
    return htmlspecialchars($text, ENT_QUOTES,'UTF-8');
}

function validateInput($data){
    $errors = [];

    if ($data['name'] === '' || strlen($data['name']) < 2){
        $errors['name'] = 'Введите имя';
    }
    if ($data['department'] === ''){
        $errors['department'] = 'Введите отдел';
    }
    if ($data['experience'] === '' || $data['experience'] < 0){
        $errors['experience'] = 'Введите стаж';
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
        $errors['email'] = 'Введите корректный email';
    }
    return $errors;

}
