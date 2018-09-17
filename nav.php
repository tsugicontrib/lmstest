<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

li {
    float: left;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

/* Change the link color to #111 (black) on hover */
li a:hover {
    background-color: #111;
}

.active {
    background-color: #4CAF50;
}
</style>
<?php
$currentPage= basename($_SERVER["SCRIPT_NAME"]);
?>
<div>
<ul>
<li><a href="index.php" <?php if ( $currentPage == 'index.php' ) echo('class="active"'); ?>>Home</a></li>
<li><a href="grades.php" <?php if ( $currentPage == 'grades.php' ) echo('class="active"'); ?>>Send Grade</a></li>
<li><a href="tokens.php" <?php if ( $currentPage == 'tokens.php' ) echo('class="active"'); ?>>Tokens</a></li>
</ul>
</div>
