<?php include 'header.php'; ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php 
                $pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
                if (!isset($_GET['query'])):
            ?>
            <div class="span9">
                <?php
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
                            $description = (isset($_POST['description'])) ? htmlentities($_POST['description'], ENT_QUOTES) : '';
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

                            $sql = "INSERT INTO pastebin (title, language, source, created, description, slug, revision)
                            VALUES ('{$title}', '{$language}', '{$source}', '{$created}', '{$description}', '{$slug}', '{$revision}')";

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
                    }
                ?>
                <?php if (isset($paste)): ?>
                <div class="row-fluid">
                    <div class="span12 well well-small">
                        <h2>
                            <?php echo $paste->title; ?><br />
                            <small>
                                Revision #<?php echo $paste->revision; ?> -
                                Created on <?php echo date('Y-m-d', strtotime($paste->created)); ?>
                            </small>
                        </h2>
                        <h4>Description</h4>
                        <p><?php echo nl2br($paste->description); ?></p>
                        <hr>
                        <?php echo $geshi->parse_code(); ?>
                    </div>
                </div>
                <?php endif; ?>
                <form method="post" action="" class="well well-small">
                    <div class="row-fluid">
                        <div class="span9">
                            <label>Title:</label>
                            <input type="text" class="input-xlarge span12" name="title" value="<?php echo (!empty($paste)) ? $paste->title : ''; ?>">
                        </div>
                        <div class="span3">
                            <label>Language:</label>
                            <select name="language" class="input-xlarge span12">
                                <option value="php"<?php echo (!empty($paste) && ($paste->language == 'php')) ? ' selected' : ''; ?>>PHP</option>
                                <option value="python"<?php echo (!empty($paste) && ($paste->language == 'python')) ? ' selected' : ''; ?>>Python</option>
                                <option value="javascript"<?php echo (!empty($paste) && ($paste->language == 'javascript')) ? ' selected' : ''; ?>>JavaScript</option>
                            </select>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <label>Description:</label>
                            <textarea class="input-xlarge span12" style="height:150px;" name="description"><?php echo (!empty($paste)) ? $paste->description : ''; ?></textarea>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <label>Code:</label>
                            <textarea class="input-xlarge span12" name="code"><?php echo (!empty($paste)) ? $paste->source : ''; ?></textarea>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <input type="submit" class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
            <?php else: ?>
            <div class="span9">
                <?php
                    $query = "%{$_GET['query']}%";
                    $stmt = $pdo->prepare('SELECT title,language,slug,revision,created FROM pastebin WHERE title LIKE :title');
                    $stmt->execute(array(':title' => $query));
                    if ($stmt->rowCount() > 0): 
                ?>
                <h2>
                    <small>Found <strong><?php echo $stmt->rowCount(); ?></strong> pastes matching "<?php echo htmlentities($_GET['query']); ?>"</small>
                </h2>
                <table class="table table-condensed table-striped">
                    <thead>
                        <th>Title</th>
                        <th>Language</th>
                        <th>Revision</th>
                        <th>Created</th>
                    </thead>
                    <tbody>
                        <?php while ($paste = $stmt->fetch(PDO::FETCH_OBJ)): ?>
                        <tr>
                            <td><a href="?view=<?php echo $paste->slug; ?>"><?php echo $paste->title; ?></a></td>
                            <td><?php echo $paste->language; ?></td>
                            <td><?php echo $paste->revision; ?></td>
                            <td><?php echo date('Y-m-d', strtotime($paste->created)); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                No match found
                <?php endif; ?>
            </div>
            <?php endif; ?>
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
                                    $title = substr($paste->title, 0, 50);
                                    $title .= (strlen($paste->title) > 50) ? '...' : '';
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