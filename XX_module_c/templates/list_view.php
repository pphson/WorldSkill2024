<?php 
$pageTitle = isset($listTitle) ? $listTitle : 'Lyon Heritage Sites';
include 'layout_header.php'; 
?>

<div class="list-container">
    <h1><?php echo $pageTitle; ?></h1>

    <ul class="directory-list">
        <?php if (isset($contentList['folders']) && !empty($contentList['folders'])): ?>
            <?php foreach ($contentList['folders'] as $folder): ?>
                <?php 
                $folderPath = $currentRelativePath ? $currentRelativePath . '/' . $folder : $folder;
                $folderUrlParts = explode('/', $folderPath);
                $folderEncodedParts = array_map('urlencode', $folderUrlParts);
                $folderEncodedUrl = implode('/', $folderEncodedParts);
                ?>
                <li class="folder-item">
                    <a href="/XX_module_c/heritages/<?php echo $folderEncodedUrl; ?>" class="folder-link">
                        📁 <strong><?php echo htmlspecialchars($folder); ?></strong>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>

        <hr class="separator">

        <?php 
        // Biến $displayArticles hợp nhất dữ liệu từ hệ thống thư mục hoặc từ hàm Search/Tags
        $displayArticles = [];
        if (isset($articles)) {
            $displayArticles = $articles; // Dữ liệu từ Search/Tags (Đã parse sẵn)
        } elseif (isset($contentList['files'])) {
            // Dữ liệu từ hệ thống thư mục (Cần parse để lấy summary và title)
            foreach ($contentList['files'] as $fileData) {
                $parsed = parseArticle($fileData['path']);
                if (!$parsed['is_draft']) {
                    $displayArticles[] = $parsed;
                }
            }
        }
        ?>

        <?php if (!empty($displayArticles)): ?>
            <?php foreach ($displayArticles as $article): ?>
                <?php 
                $urlParts = explode('/', $article['url_path']);
                $encodedParts = array_map('urlencode', $urlParts);
                $encodedUrlPath = implode('/', $encodedParts);
                ?>
                <li class="article-item">
                    <h2>
                        <a href="/XX_module_c/heritages/<?php echo $encodedUrlPath; ?>">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </a>
                    </h2>
                    
                    <p class="article-meta">
                        <small>Date: <?php echo htmlspecialchars($article['date']); ?></small>
                    </p>
                    
                    <?php if (!empty($article['summary'])): ?>
                        <p class="article-summary">
                            <a href="/XX_module_c/heritages/<?php echo $encodedUrlPath; ?>">
                                <?php echo htmlspecialchars($article['summary']); ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No content found.</p>
        <?php endif; ?>
    </ul>
</div>

<?php include 'layout_footer.php'; ?>