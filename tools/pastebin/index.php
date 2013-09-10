<?php include 'header.php'; ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span9">
                <?php
                    $pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
                    if (!empty($_POST) || isset($_GET['view'])) {
                        if (isset($_GET['view'])) {
                            $revision = (isset($_GET['revision'])) ? (int) ($_GET['revision'] + 0) : 1;
                            $slug = preg_replace('/[^a-zA-Z0-9]+/', '', $_GET['view']);
                            $stmt = $pdo->prepare('SELECT * FROM pastebin WHERE slug=:slug AND revision=:revision');
                            if (!$stmt->execute(array(':slug' => $slug, ':revision' => $revision))) {
                                header("Location: index.php");
                            } else {
                                $paste = $stmt->fetch(PDO::FETCH_OBJ);
                            }
                        }
                        if (isset($_POST) && (!empty($_POST['code']))) {
                            $created = date('Y-m-d H:i:s');
                            $title = (isset($_POST['title']) && (!empty($_POST['title']))) ? $_POST['title'] : 'Untitled - ' . $created;
                            $language = (isset($_POST['language'])) ? $_POST['language'] : 'php';
                            $source = htmlentities($_POST['code'], ENT_QUOTES);

                            // Check if we're creating a new revision of an existing code
                            $slug = (isset($_GET['view'])) ? $_GET['view'] : null;
                            $revision = (isset($_GET['revision'])) ? (int) ($_GET['revision'] + 1) : 1;

                            // Make new slug if it's not a new revision
                            if (is_null($slug)) {
                                $bytes = openssl_random_pseudo_bytes(20);
                                $string = base64_encode($bytes);
                                $stripped = preg_replace('/[^a-zA-Z0-9]+/', '', $string);
                                $slug = substr($stripped, 0, 10);
                            }

                            $sql = "INSERT INTO pastebin (title, language, source, created, slug, revision)
                            VALUES ('{$title}', '{$language}', '{$source}', '{$created}', '{$slug}', '{$revision}')";
                            echo $sql;
                            if ($pdo->query($sql)) {
                                header("Location: index.php?view={$slug}&revision={$revision}");
                            } else {
                                var_dump($pdo->errorInfo());
                            }
                        }

                        include 'includes/geshi/geshi.php';
                        $geshi = new GeShi(htmlspecialchars_decode($paste->source, ENT_QUOTES), $paste->language);
                        $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
                        $geshi->set_line_style('background: #fcfcfc;', 'background: #f0f0f0;');
                        echo $geshi->parse_code();
                    }
                ?>
                <form method="post" action="" class="well well-small">
                    <label>Title:</label>
                    <input type="text" class="input-xlarge span7" name="title" value="<?php echo (!empty($paste)) ? $paste->title : ''; ?>">
                    <label>Language:</label>
                    <select name="language">
                        <option value="php"<?php echo (!empty($paste) && ($paste->language == 'php')) ? ' selected' : ''; ?>>PHP</option>
                        <option value="python"<?php echo (!empty($paste) && ($paste->language == 'python')) ? ' selected' : ''; ?>>Python</option>
                    </select>
                    <label>Code:</label>
                    <textarea class="input-xlarge span12" name="code"><?php echo (!empty($paste)) ? $paste->source : ''; ?></textarea>
                    <input type="submit" class="btn btn-success" value="Save">
                </form>
            </div>
            <div class="span3 well well-small">
                <table class="table table-condensed table-striped">
                <?php
                    $stmt = null;
                    $stmt = $pdo->query('SELECT title,created,slug,revision FROM pastebin ORDER BY id DESC');
                    $stmt->execute();

                    while ($paste = $stmt->fetch(PDO::FETCH_OBJ)):
                ?>
                        <tr>
                            <td>
                                <?php
                                    $title = substr($paste->title, 0, 25);
                                    $title .= (strlen($paste->title) > 25) ? '...' : '';
                                ?>
                                <a href="index.php?view=<?php echo $paste->slug; ?>&amp;revision=<?php echo $paste->revision; ?>"><?php echo $title; ?> <small>(Rev: <?php echo $paste->revision; ?>)</small></a>
                                <span class="pull-right"><?php echo date('Y-m-d', strtotime($paste->created)); ?></span>
                            </td>
                        </tr>
                <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
<?php include 'footer.php'; ?>