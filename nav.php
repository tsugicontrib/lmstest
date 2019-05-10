<style>
#nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

#nav li {
    float: left;
}

#nav li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

/* Change the link color to #111 (black) on hover */
#nav li a:hover {
    background-color: #111;
}

.active {
    background-color: #4CAF50;
}
</style>
<?php
$currentPage= basename($_SERVER["SCRIPT_NAME"]);
?>
<div id="nav">
<ul>
<li><a href="index.php" <?php if ( $currentPage == 'index.php' ) echo('class="active"'); ?>>Home</a></li>
<li><a href="lineitem.php" <?php if ( $currentPage == 'lineitem.php' ) echo('class="active"'); ?>>Grade Send</a></li>
<li><a href="roster.php" <?php if ( $currentPage == 'roster.php' ) echo('class="active"'); ?>>Names And Roles</a></li>
<li><a href="lineitems.php" <?php if ( $currentPage == 'lineitems.php' ) echo('class="active"'); ?>>LineItems</a></li>
<li><a href="interactive.php" <?php if ( $currentPage == 'interactive.php' ) echo('class="active"'); ?>>Interact With LineItems</a></li>
</ul>
</div>
