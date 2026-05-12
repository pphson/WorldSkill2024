<?php 
$pageTitle = $articleData['title'];
include 'layout_header.php'; 
?>

<article class="heritage-post">
    <div class="cover-wrapper" id="coverWrapper">
        <img src="<?php echo $articleData['cover']; ?>" alt="Cover image for <?php echo htmlspecialchars($articleData['title']); ?>" class="cover-image" id="coverImage">
    </div>

    <div class="content-container">
        <aside class="meta-aside">
            <p><strong>Date:</strong> <?php echo htmlspecialchars($articleData['date']); ?></p>
            <?php if (!empty($articleData['tags'])): ?>
                <p><strong>Tags:</strong> 
                    <?php foreach ($articleData['tags'] as $tag): ?>
                        <a href="/XX_module_c/tags/<?php echo urlencode($tag); ?>"><?php echo htmlspecialchars($tag); ?></a>
                    <?php endforeach; ?>
                </p>
            <?php endif; ?>
            <?php if ($articleData['is_draft']): ?>
                <p><strong>Status:</strong> Draft</p>
            <?php endif; ?>
        </aside>

        <div class="main-content">
            <h1 class="post-title"><?php echo htmlspecialchars($articleData['title']); ?></h1>
            <div class="post-body">
                <?php echo $articleData['html_content']; ?>
            </div>
        </div>
    </div>
</article>

<?php include 'layout_footer.php'; ?>