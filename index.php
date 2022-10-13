<?php
$filename = __DIR__ . '/data/articles.json';
$articles = [];
$categories = [];

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$selectedCat = $_GET['cat'] ?? '';

if (file_exists($filename)) {
    $articles = json_decode(file_get_contents($filename), true) ?? [];
    // permet de retourner un tableau contenant uniquement les catégories
    $cattmp = array_map(fn ($a) => $a['category'], $articles);

    // Nous parcourons le tableau des catégories et partons d'un tableau vide []
    // Pour chaque élément $cat du tableau des catégories. Nous vérifions s'il y a déjà une clé pour la catégorie dans le tableau $acc.
    // S'il y en a une nous incrémentons la valeur. S'il n'y en a pas nous créons un élément avec comme clé la catégorie et comme valeur 1.
    // Nous obtenons un tableau associatif de la forme ['category' => nombre].
    $categories = array_reduce($cattmp, function ($acc, $cat) {
        if (isset($acc[$cat])) {
            $acc[$cat]++;
        } else {
            $acc[$cat] = 1;
        }
        return $acc;
    }, []);


    // Nous parcourons le tableau des catégories et partons d'un tableau vide []
    // Pour chaque élément $article du tableau des articles. Nous vérifions s'il y a déjà une clé pour la catégorie dans le tableau $acc
    // S'il y en a une, nous ajoutons l'article à la fin du tableau des articles. S'il n'y en a pas nous créons un élément avec comme clé la catégorie de l'article et comme valeur un nouveau tableau contenant l'article.
    // Nous obtenons un tableau associatif de la forme ['category' => [article1, article2…]].
    $articlePerCategories = array_reduce($articles, function ($acc, $article) {
        if (isset($acc[$article['category']])) {
            $acc[$article['category']] = [...$acc[$article['category']], $article];
        } else {
            $acc[$article['category']] = [$article];
        }
        return $acc;
    }, []);
    // echo '<pre>';
    // print_r($articlePerCategories);
    // echo '</pre>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once './includes/head.php' ?>
    <title>Blog</title>
    <link rel="stylesheet" href="./assets/css/index.css">
</head>

<body>
    <?php require_once './includes/header.php' ?>
    <div class="container">
        <div class="content">
            <div class="newsfeed-container">
                <!-- Liste des catégories -->
                <ul class="category-container">
                    <li class=<?= $selectedCat ? '' : 'cat-active' ?>><a href="/">Tous les articles <span class="small">(<?= count($articles) ?>)</span></a></li>
                    <?php foreach ($categories as $catName => $catNum) : ?>
                        <li class=<?= $selectedCat ===  $catName ? 'cat-active' : '' ?>><a href="/?cat=<?= $catName ?>"> <?= ucfirst($catName) ?><span class="small"> (<?= $catNum ?>)</span> </a></li>
                    <?php endforeach; ?>
                </ul>
                <div class="newsfeed-content">
                    <?php if (!$selectedCat) : ?>
                        <?php foreach ($categories as $cat => $num) : ?>
                            <h2><?= ucfirst($cat) ?></h2>
                            <div class="articles-container">
                                <!-- On remplace $article par $articlePerCategories[$cat] -->
                                <?php foreach ($articlePerCategories[$cat] as $article) : ?>
                                    <div class="article block">
                                        <div class="overflow">
                                            <div class="img-container">
                                                <img src="<?= $article['image'] ?>" class="img-fluid" alt="<?= $article['title'] ?>">
                                            </div>
                                        </div>
                                        <div class="content-container">
                                            <h3><?= $article['title'] ?></h3>
                                            <div class="p-20">
                                                <a class="btn btn-primary " href="/show-article.php?id=<?= $article["id"] ?>">Détails</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <h2><?= ucfirst($selectedCat) ?></h2>
                        <div class="articles-container">
                            <?php foreach ($articlePerCategories[$selectedCat] as $article) : ?>
                                <div class="article block">
                                    <div class="overflow">
                                        <div class="img-container">
                                            <img src="<?= $article['image'] ?>" class="img-fluid" alt="<?= $article['title'] ?>">
                                        </div>
                                    </div>
                                    <div class="content-container">
                                        <h3><?= $article['title'] ?></h3>
                                        <div class="p-20">
                                            <a class="btn btn-primary " href="/show-article.php?id=<?= $article["id"] ?>">Détails</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>