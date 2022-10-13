<?php
$filename = __DIR__ . '/data/articles.json';
$articles = [];
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if (!$id) {
    header('Location: /');
} else {
    if (file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
        $articleIndex = array_search($id, array_column($articles, 'id'));
        $article = $articles[$articleIndex];
    }
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/assets/css/show-article.css">
    <title>Article</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="article-container">
                <a class="article-back" href="/">Retour à la liste des articles</a>
                <div class="article-cover-img">
                    <img src="<?= $article['image'] ?>" alt="<?= $article['title'] ?>">
                </div>
                <h2 class="article-title"><?= $article['title'] ?></h2>
                <span class="article-category"><?= ucfirst($article['category']) ?></span>
                <div class="separator"></div>
                <p class="article-content"><?= $article['content'] ?></p>
                <div class="btn-actions">
                    <a class="btn btn-secondary btn-small" href="form-article.php?id=<?= $article['id'] ?>"> Modifier</a>
                    <a class="btn btn-danger btn-small" href="remove-article.php?id=<?= $article['id'] ?>"> Supprimer</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>