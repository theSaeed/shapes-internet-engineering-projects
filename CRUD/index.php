<?php
    function getPDO() {
        $host = '127.0.0.1';
        $db = 'shapes';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
            die("This is the end!");
        }
        return $pdo;
    }
    $db = getPDO();

    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'delete') {
            $command = 'DELETE FROM crud WHERE shapeId=?';
            $params = array($_GET['shapeId']);
            $stmt = $db->prepare($command);
            $stmt->execute($params);
            header("Location: index.php");
        } else if ($_GET['action'] == 'create') {
            $command = 'INSERT INTO crud (shapeName, shapeSides) VALUES (?, ?)';
            $params = array($_GET['shapeName'], $_GET['shapeSides']);
            $stmt = $db->prepare($command);
            $stmt->execute($params);
            header("Location: index.php");
        } else if ($_GET['action'] == 'update') {
            $command = 'UPDATE crud SET shapeName=?, shapeSides=? WHERE shapeId=?';
            $params = array($_GET['shapeName'], $_GET['shapeSides'], $_GET['shapeId']);
            $stmt = $db->prepare($command);
            $stmt->execute($params);
            header("Location: index.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shapes CRUD</title>
        <link rel="icon" type="image/svg+xml" href="../nav-triangle.svg">
        <link rel="stylesheet" href="styles.css"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Yaldevi:wght@600&display=swap" rel="stylesheet">
    </head>
    <body>
        <div id="header">
            <div id="header-bg"></div>
            <div class="container">
                <div id="title">
                    <h1><a href="index.php" style="color:white; opacity:1; height:0; margin:0; border:none;">Shapes</a></h1>
                </div>
            </div>
        </div>
        <div class="body">
            <div class="container">
                
                <?php
                    if (isset($_GET['action']) && $_GET['action'] == 'create-form') { ?>
                        <form action="index.php" method="get">
                            <div class="row">
                                <div class="item">
                                    <input type="hidden" name="action" value="create"/>
                                    <input type="text" class="column shape-name" name="shapeName" placeholder="Name"/>
                                    <input type="text" class="column shape-sides" name="shapeSides" placeholder="Sides"/>
                                </div>
                            </div>
                            <div class="row submit-row">
                                <div class="item">
                                    <span id="submit-border">
                                        <input type="image" src="Create.svg" alt="Create" id="submit" class="create-button"/>
                                    </span>
                                </div>
                            </div>
                        </form>
                        <?php
                    } else if (isset($_GET['action']) && $_GET['action'] == 'update-form') { ?>
                        <form action="index.php" method="get">
                            <div class="row">
                                <div class="item">
                                    <input type="hidden" name="action" value="update"/>
                                    <input type="hidden" name="shapeId" value="<?php echo $_GET['shapeId'];?>"/>
                                    <input type="text" class="column shape-name" name="shapeName" placeholder="Name" value="<?php echo $_GET['shapeName'];?>"/>
                                    <input type="text" class="column shape-sides" name="shapeSides" placeholder="Sides" value="<?php echo $_GET['shapeSides'];?>"/>
                                </div>
                            </div>
                            <div class="row submit-row">
                                <div class="item">
                                    <span id="submit-border">
                                        <input type="image" src="Update.svg" alt="Update" id="submit" class="update-button"/>
                                    <span>
                                </div>
                            </div>
                        </form>
                        <?php
                    } else {
                        $command = 'SELECT * FROM crud';
                        $stmt = $db->prepare($command);
                        $stmt->execute();
                        while ($row = $stmt->fetch()) { ?>
                            <div class="row">
                                <div class="item">
                                    <div class="column shape-name">
                                        <p><?php echo $row['shapeName'];?></p>
                                    </div>
                                    <div class="column shape-sides">
                                        <p><?php echo $row['shapeSides'];?></p>
                                    </div>
                                    <a href="index.php?action=update-form&shapeId=<?php echo $row['shapeId'];?>&shapeName=<?php echo $row['shapeName'];?>&shapeSides=<?php echo $row['shapeSides'];?>"
                                    title="Update <?php echo $row['shapeName'];?>">
                                        <span class="update-button"><img src="Update.svg" alt="Update"/></span>
                                    </a>
                                    <a href="index.php?action=delete&shapeId=<?php echo $row['shapeId'];?>"
                                    title="Delete <?php echo $row['shapeName'];?>">
                                        <span class="delete-button"><img src="Delete.svg" alt="Delete"/></span>
                                    </a>
                                </div>
                            </div>
                            <?php
                        } ?>
                        <div class="row submit-row">
                            <div class="item">
                                <a href="index.php?action=create-form" title="Create">
                                    <span class="create-button"><img src="Create.svg" alt="Create"/></span>
                                </a>
                            </div>
                        </div>
                        <?php                        
                    }
                ?>

                <div id="footer">
                    <div id="designer">
                        <p>
                            Designed by
                        </p>
                        <p>
                            <strong>Saeed Ahmadnia</strong>
                        </p>
                    </div>
                    <div id="date">
                        <p>
                            November 2021
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>