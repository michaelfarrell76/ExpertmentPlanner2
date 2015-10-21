<!DOCTYPE html>

<html>

    <head>

        <link href="/Planner/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="/Planner/css/bootstrap-theme.min.css" rel="stylesheet"/>
        <link href="/Planner/css/styles.css" rel="stylesheet"/>
        <link href="/Planner/css/jquery.sidr.dark.css" rel="stylesheet"/>

        <?php if (isset($title)): ?>
            <title>Experiment Planner <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>Experiment Planner</title>
        <?php endif ?>

        <script src="/Planner/js/jquery-1.10.2.min.js"></script>
        <script src="/Planner/js/bootstrap.min.js"></script>
        <script src="/Planner/js/scripts.js"></script>
        <script src="/Planner/js/jquery.sidr.min.js"></script>

    </head>

    <body>

        <div class="container">
				
            <div id="top">
            
                <a id="simple-menu" class="topbarmenu" href="#mainmenu">Plan Your Experiments</a>
            </div>

            <div id="middle"> 
 
            <div class = 'hidemenu' id="mainmenu">
  <!-- Your content -->
  <ul>
    <li><a href="/Planner/index.php">Home</a></li>
    <li><a href="/Planner/permutations.php">Standard Experiment Variable Planner</a></li>
    <li><a href="/Planner/antibodies.php">Multiplexed Immunostaining Planner</a></li>
  </ul>
</div>
 
<script>
//www.sidr.com
$(document).ready(function() {
  $('#simple-menu').sidr({
      name: 'sidr-left',
      side: 'left' ,
      width: '50',
      source: '#mainmenu'
    });
});
</script>
