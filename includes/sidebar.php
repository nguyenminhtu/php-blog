<!-- sidebar -->
<div class="col-md-4" id="sidebar">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-bookmark"></span> <b style="font-size: 20px;">Categories</b>
        </div>
        <div class="panel-body list-group">
            <?php
                $q = "SELECT c.cid, c.cname FROM categories AS c ORDER BY c.corder DESC";
                $r = mysqli_query($dbc, $q);
                confirm_query($r, $q);

                if (mysqli_num_rows($r) > 0) {
                    while ($cates = mysqli_fetch_array($r, MYSQLI_ASSOC)) { ?>
                        <a href="<?php echo $cates['cid'] ?>-<?php echo urlencode($cates['cname']); ?>.aspx" class='list-group-item <?php hightlight_sidebar($cates['cname']); ?>'><?php echo $cates['cname']; ?></a>
            <?php
                    }
                }
            ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-bookmark"></span> <b style="font-size: 20px;">Latest post</b>
        </div>
        <div class="panel-body" style="padding-bottom: 0;">
            <?php
                $q1 = "SELECT p.pid, p.ptitle, p.pimage, COUNT(cm.cmid) AS comment FROM posts AS p LEFT JOIN comments AS cm ON p.pid = cm.pid GROUP BY p.ptitle ORDER BY p.pdate DESC LIMIT 5";
                $r1 = mysqli_query($dbc, $q1);
                confirm_query($r1 ,$q1);

                if (mysqli_num_rows($r1) > 0) {
                    while ($posts = mysqli_fetch_array($r1, MYSQLI_ASSOC)) {
                        echo "
                        <div class='row item'>
                            <div class='col-md-3'>
                                <p>
                                    <img src='uploads/images/{$posts['pimage']}' alt='Post image' class='img-responsive img-circle'>
                                </p>
                            </div>
                            <div class='col-md-9'>
                                <p style='margin-top: 5px;'>
                                    <a class='pull-left' href='{$posts['pid']}-".urlencode($posts['ptitle']).".html'>{$posts['ptitle']}</a>
                                    <span class='badge pull-right'>{$posts['comment']}</span>
                                    <span class='clearfix'></span>
                                </p>
                            </div>
                        </div>      
                    ";
                    }
                }
            ?>
        </div>
    </div>
</div> <!-- end sidebar -->