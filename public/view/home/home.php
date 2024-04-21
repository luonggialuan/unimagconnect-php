<!-- search popup area -->
<div class="search-popup">
    <!-- close button -->
    <button type="button" class="btn-close" aria-label="Close"></button>
    <!-- content -->
    <div class="search-content">
        <div class="text-center">
            <h3 class="mb-4 mt-0">Press ESC to close</h3>
        </div>
        <!-- form -->
        <form class="d-flex search-form">
            <input class="form-control me-2" type="search" placeholder="Search and press enter ..." aria-label="Search">
            <button class="btn btn-default btn-lg" type="submit"><i class="icon-magnifier"></i></button>
        </form>
    </div>
</div>


<style>
.card {
    transition: transform 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}
</style>

<div class="container">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <!-- <div class="row"> -->
        <?php
    // Select article that public 
    $sql_articles_approved = "SELECT * FROM `articles`
            INNER JOIN users ON articles.authorId = users.userId
            WHERE articles.showStatus = 1";
    $result = $conn->query($sql_articles_approved);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
        <div class="col">
            <div class="card h-100 shadow rounded">
                <img src="<?= $row['image'] ?>" class="card-img-top" alt="<?= basename($row['image']) ?>">
                <div class="card-body">
                    <h5 class="card-title">
                        <?= $row["title"] ?>
                    </h5>
                    <p class="card-text">
                        <?= $row['content'] ?>
                    </p>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <small class="text-body-secondary">
                        <?= $row['publicDate'] ?>
                    </small>
                    <form action="download.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="articlePublicId" id="articlePublicId"
                            value="<?= $row['articleId'] ?>">
                        <button type="submit" class="btn btn-primary btn-floating">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-download" viewBox="0 0 16 16">
                                <path
                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                <path
                                    d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        }
    } else {
        echo "No data found!";
    }
    ?>
        <!-- </div> -->
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-r7FC9f2NpVa0Id9jzj+pUitCgOrQ3MP0wZgnYb3zEoVCFSF1SLDCcuA5bUzBndpy" crossorigin="anonymous">
</script>