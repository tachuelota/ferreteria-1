<header id="header"> 
	<img src="<?php echo PATH; ?>/herramientas/imagenes/Logo_Global.png"/>
</header>
<script type="text/javascript">
    $(document).ready(function() {
        $('.menu').gexmenu();
    });
</script>
<ul class="menu blue">
    <li><a href="<?php echo PATH."/pedidos.php"; ?>"><i class="fa fa-truck"></i>Pedidos</a></li>
    <li><a href="<?php echo PATH."/familias.php"; ?>"><i class="fa fa-sitemap"></i>Familias</a></li>
</ul>