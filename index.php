<?php 
// Connection config
include("connection.php");

// If url contain link
if(htmlspecialchars($_GET['u']) != null){
    // Connect to db
    try {
        $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        
        $serviceUrl = htmlspecialchars($_GET['u']);

        // Prepared statesment
        $stmt = $dbh->prepare("SELECT `realUrl` FROM `pair` where serviceUrl = ?");
        if ($stmt->execute(array($serviceUrl))) {
            while ($row = $stmt->fetch()) {
                $realUrl = $row['realUrl'];
            }
        }
        else {
            print_r($dbh->errorInfo());
        }

        // if url not found
        if($realUrl == null){
            print("404 - Not found");
        }
        else{
            // Redirect to url
            header("Location: http://$realUrl"); 
        }
        

        /* Close connection */
        $dbh = null;
        exit;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}
else{
// Connect to db
    try {
        $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        

        // Prepared statesment
        $stmt = $dbh->query("SELECT `id` FROM `pair`");
        while ($row = $stmt->fetch()) {
            $counter = $row['id'];
        }

        /* Close connection */
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Link Shortener</title>
    <link rel="stylesheet" href="./static/bootstrap.min.css">
</head>

<body>
    <div class="text-center display-3">Link Shortener</div>
    <div class="container">
        <div class="row">
            <div class="col col-sm-6">
                <h1>Create new link</h1>
                <div class="mb-3"></div>
                <form action="create.php" method="get">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">http://</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Link" aria-label="link"
                            aria-describedby="basic-addon1" name="link">
                        <div class="input-group-append">
                            <input type="submit" value="Go" class="btn btn-outline-secondary">
                        </div>
                        
                    </div>

                </form>

            </div>
            <div class="col col-sm-6">
                <h1>What is it?</h1>
                <div class="mb-2"></div>
                <p>Shortening links allows you to send shorter links convenient for quick writing on paper or dictation</p>
                <h2>Statistics</h2>
                <p>Links already shortened: <strong> <?php print($counter); ?> </strong> </p>
                <p>Total possible 5-digit links: 36^5 = <strong>60466176</strong> combination  </p>   
            </div>
        </div>
    </div>
    <script src="./static/bootstrap.bundle.min.js"></script>
    <script src="./static/bootstrap.min.js"></script>
</body>

</html>