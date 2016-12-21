<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Social Media Fetcher</title>
    </head>
    <body>
        <strong>Social Media Fetcher</strong><br>
        <fieldset>
            <legend>SocialMedia Details:</legend>
            <form id="socialForm" name="socialForm" action="" method="post">
                <label for="username">UserName/Email: </label><br />
                <input type="text" name="username" value="" id="username" required /><br />
                <label for="username">Password: </label><br />
                <input type="password" name="password" value="" id="password" required /><br/><br/>
                <input type="submit" name="submitBtn" value="Synchronize Contacts" id="submitBtn" />
            </form>
        </fieldset>
        <?php
        if ($handle = opendir('.')) {
            $blacklist = array('.', '..', 'index.php', 'Netbeans');
            while (false !== ($file = readdir($handle))) {
                if (!in_array($file, $blacklist)) {
                    echo "<a href='$file'>$file</a>\n\r<br>";
                }
            }
            closedir($handle);
        }
        ?>
    </body>
</html>
