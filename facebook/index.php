<?php
session_start();
require_once 'helpers.php';
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <title>Login with Facebook</title>
        <link href="http://www.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet"> 
    </head>
    <body>
        <?php if ($_SESSION['FBID']): ?>      <!--  After user login  -->
            <div class="container">
                <div class="span4">
                    <ul class="nav nav-list">
                        <li class="nav-header">Image</li>
                        <li><img src="https://graph.facebook.com/<?php echo $_SESSION['FBID']; ?>/picture"></li>
                        <li class="nav-header">Facebook ID</li>
                        <li><?php echo $_SESSION['FBID']; ?></li>
                        <li class="nav-header">Facebook fullname</li>
                        <li><?php echo $_SESSION['FULLNAME']; ?></li>
                        <li class="nav-header">Facebook Email</li>
                        <li><?php echo $_SESSION['EMAIL']; ?></li>
                        <div><a href="logout.php">Logout</a></div>
                    </ul>
                </div>
            </div>
            <?php
            $pages = $_SESSION['pages'];
            ?>
        <center>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>CATEGORY</th>
                    <th>FANS</th>
    <!--                    <th>PERMISSIONS</th>
                    <th>ACCESS TOKEN</th>-->
                </tr>
                <?php
                foreach ($pages as $key => $value) :
                    ?>
                    <tr>
                        <td><?= $value->id; ?></td>
                        <td><?= $value->name; ?></td>
                        <td><?= $value->category; ?></td>
                        <td>
                            <pre>
                                <?php
                                //print_r(fb_fans($value->id, $value->access_token));
                                print_r(get_likes_count($value->id,$value->access_token));
                                ?>
                            </pre>
                        </td>
                        <!--<td>< print_r($value->perms);
                        ?></td>
                        <td>< $value->access_token; ?></td>-->
                    </tr>
                    <?php
                endforeach;
                ?>
            </table>
        </center>
    <?php else: ?>     <!-- Before login --> 
        <div class="container">
            <div>
                <br>
                Not Connected
                <a href="fbconfig.php">Login with Facebook</a></div>
        <?php endif ?>
    </body>
</html>
