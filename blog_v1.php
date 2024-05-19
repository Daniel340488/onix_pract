<?php

function generateFilename($title, $author) {
    $timestamp = date("YmdHis");
    $filename = strtolower($title). "-". strtolower($author). "-". $timestamp. ".md";
    return $filename;
}

function generateBlogPost($title, $author, $categories, $date, $content) {
    $categoriesString = (count($categories) > 1)? "[". implode(", ", $categories). "]" : $categories[0];
    $template = "---\ntitle: \"$title\"\nauthor: \"$author\"\ncategory: $categoriesString\ndate: \"$date\"\n---\n\nВміст блогу:\n\n$content";
    return $template;
}

function validateInput($input) {
    $trimmedInput = trim($input);
    if (empty($trimmedInput)) {
        echo "Поле не може бути порожнім.\n";
        return false;
    }
    if (mb_strlen($trimmedInput) < 3) {
        echo "Довжина рядка має бути не менше 3 символів.\n";
        return false;
    }
    return true;
}

function promptInput($message) {
    do {
        echo $message;
        $input = trim(fgets(STDIN));
    } while (!validateInput($input));
    return $input;
}

$title = promptInput("Введіть заголовок блог-посту: ");
$author = promptInput("Введіть ім'я автора: ");
$categoryInput = promptInput("Введіть категорії(є можливість введення декількох категорій через кому): ");
$categories = array_map('trim', explode(',', $categoryInput));
echo "Введіть вміст блог-посту:\n";
$content = "";
while ($line = trim(fgets(STDIN))) {
    $content.= $line. "\n";
}

$currentDirectory = getcwd();
echo "Поточний каталог: $currentDirectory\n";

echo "Введіть '1' якщо хочете зберегти файл у поточному каталозі, або '2', щоб створити новий: ";
$choice = trim(fgets(STDIN));

if (strtolower($choice) === '1') {
    $outputDirectory = $currentDirectory;
} else {
    echo "Введіть ім'я нового каталогу: ";
    $newDirectory = trim(fgets(STDIN));
    if (!file_exists($newDirectory)) {
        mkdir($newDirectory, 0777, true);
    }
    $outputDirectory = $newDirectory;
}

$currentDate = date("Y-m-d");

$filename = generateFilename($title, $author);
$template = generateBlogPost($title, $author, $categories, $currentDate, $content);
$filePath = $outputDirectory. "/". $filename;

file_put_contents($filePath, $template);

echo "Файл створено за шляхом: $filePath\n";
