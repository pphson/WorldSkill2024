<!DOCTYPE html>
<html lang="en"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Lyon Heritage Sites'; ?></title>
    
    <meta property="og:title" content="<?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Lyon Heritage Sites'; ?>">
    <meta property="og:description" content="Discover the beautiful heritage sites of Lyon, France.">
    <?php if (isset($articleData['cover'])): ?>
        <meta property="og:image" content="<?php echo htmlspecialchars($articleData['cover']); ?>">
        <meta name="twitter:card" content="summary_large_image">
    <?php endif; ?>
    <?php if (isset($articleData['url_path'])): ?>
        <meta property="og:url" content="http://wsXX.worldskills.org/XX_module_c/heritages/<?php echo htmlspecialchars($articleData['url_path']); ?>">
    <?php endif; ?>

    <link rel="stylesheet" href="/XX_module_c/assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="/XX_module_c/">Home</a>
            <form action="/XX_module_c/index.php" method="GET" class="search-form">
                <input type="hidden" name="url" value="search">
                <label for="searchInput" class="sr-only">Search</label>
                <input type="text" id="searchInput" name="q" placeholder="Search title or content (use / for OR)..." aria-label="Search">
                <button type="submit">Search</button>
            </form>
        </nav>
    </header>
    <main>