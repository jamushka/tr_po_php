<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
require_once __DIR__ . '/../src/functions.php';

//в шапку
$appTitle = 'Учёт сотрудников';
$appVersion = '1.0';
$currentDate = date('d.m.Y');
$rawRows = loadEmployees(__DIR__ . '/../data/employees.csv');
$employees = mapEmployeeRows($rawRows);

//для формы
$errors = [];
$successMessage = '';
$formData = [
    'name' => '',
    'department' => '',
    'experience' => '',
    'email' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $formData['name'] = trim($_POST['name'] ?? '');
    $formData['department'] = trim($_POST['department'] ?? '');
    $formData['experience'] = trim($_POST['experience'] ?? '');
    $formData['email'] = trim($_POST['email'] ?? '');
    $errors = validateInput($formData);
    if (empty($errors)){
        $employees[] = [
            'id' => count($employees) + 1,
            'name' => $formData['name'],
            'department' => $formData['department'],
            'experience' => (int)$formData['experience'],
            'email' => $formData['email'],
        ];
        $successMessage = 'Сотрудник успешно добавлен!';
        $formData['name'] = '';
        $formData['department'] = '';
        $formData['experience'] = '';
        $formData['email'] = '';
    
    }

}
//фильтр по отделу
$departmentFilter = trim($_GET['department'] ?? '');
if ($departmentFilter !== ''){
        $filtered = [];
        foreach ($employees as $emp){
            if(stripos($emp['department'], $departmentFilter) !== false) {
                $filtered[] = $emp;
            }
        }
        $employees = $filtered;  
}
?>






<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">  
    <title>Практика 4</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f4f6f9;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        main {
            max-width: 800px;
            margin: 0 auto;
        }
        .head {
            text-align: left;
            margin-bottom: 20px;
            background: rgb(169, 165, 248);
            border: 1px solid #467cc2;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }


        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .form-row {
            margin-bottom: 12px;
        }
        label {
            display: inline-block;
            width: 80px;
            font-weight: 500;
        }
        input {
            padding: 6px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        button {
            padding: 7px 14px;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1d4ed8;
        }
        form {
            margin-bottom: 20px;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background-color: #f8fafc;
        }
        .error {
            color: #dc2626;
            font-size: 0.9em;
            margin-left: 8px;
        }
        .success {
            color: #16a34a;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <main>
        <header>
            <div class="head">
                <h1><?php echo e($appTitle); ?></h1>
                <p>Версия: <?php echo e($appVersion); ?> </p>
                <p>Дата: <?php echo e($currentDate); ?> </p>
            </div>
        </header>
        </header>
        <form method="POST">
            <?php if ($successMessage): ?>
                <p> style="color: green;"<?= e($successMessage) ?></p>
            <?php endif; ?>
            <div>
                <section class="card">
                <label>Имя:</label>
                <input type="text" name="name" value="<?= e($formData['name']) ?>">
                <?php if (isset($errors['name'])): ?>
                <span style="color: red;"><?= e($errors['name']) ?></span>
                 <?php endif; ?>
                 </section>
            </div>
            <div>
                <section class="card">
                    <label>Отдел:</label>
                <input type="text" name="department" value="<?= e($formData['department']) ?>">
                <?php if (isset($errors['department'])): ?> 
                <span style="color: red;"><?= e($errors['department']) ?></span>
                 <?php endif; ?>   
                 </section> 
            </div>
            <div> 
                <section class="card">
                <label>Стаж:</label>
                <input type="number" name="experience" value="<?= e($formData['experience']) ?>">
                <?php if (isset($errors['experience'])): ?>
                <span style="color: red;"><?= e($errors['experience']) ?></span>
                 <?php endif; ?>
                </section>
            </div>
            <div>   
                <section class="card">
                <label>Email:</label>
                <input type="email" name="email" value="<?= e($formData['email']) ?>">
                <?php if (isset($errors['email'])): ?>
                <span style="color: red;"><?= e($errors['email']) ?></span>
                 <?php endif; ?>
                </section>
            </div>
            <button type="submit">Добавить</button>
        </form>
        <form method = "GET">
            <input type="text" name="department" value="<?= e($departmentFilter) ?>">
            <button type="submit">Фильтр по отделу</button>
            <a href="index.php">Сбросить</a>
        </form>
        <table>
            <tr><th>ID</th><th>Имя</th><th>Отдел</th><th>Стаж</th><th>Email</th></tr>
            <?php if (empty($employees)): ?>
            <tr><td colspan="5">Сотрудники не найдены</td></tr>
            <?php else:foreach ($employees as $emp): ?>
                    <tr>
                        <td><?= e((string)$emp['id']) ?></td>
                        <td><?= e($emp['name']) ?></td>
                        <td><?= e($emp['department']) ?></td>
                        <td><?= e((string)$emp['experience']) ?></td>
                        <td><?= e($emp['email']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </main>
</body>
</html>