<!DOCTYPE html>
<html>
    <head>
        <title>
            Archived Posts - For Blogs
        </title>
         <!--
        These below style sheet, I downloaded it from the Internet to make the archives look more fancy.
        -->
        <link rel="stylesheet" href="css/jquery.treeview.css" />
        <style>
            *,html,body{
                font-family: 'trebuchet ms', helvetica, tahoma;
                font-size:14px;
            }
            ul{
                color:#006666;
            }
            a:link,a:visited{
                color:#006666;
            }
        </style>
    </head>
    <body>
        <h2>
            Archives
        </h2>
        <hr/>
        <?php
        include_once 'archive.php';
        $archive = new archive();
        //We simply call two functions and we are done
        $archive->archives();
        $archive->show();
        ?>
        <!--
        These below scripts, I downloaded it from the Internet to make the archives look more fancy.
        -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
	<script src="js/jquery.cookie.js" type="text/javascript"></script>
	<script src="js/jquery.treeview.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/demo.js"></script>	
    </body>
</html>
