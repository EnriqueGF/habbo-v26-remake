<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright &copy; 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind. 
+---------------------------------------------------*/

if (!defined("IN_HOLOCMS")) { header("Location: ../../index.php"); exit; }

/*

	Please do not remove or edit the line defined below. If you do, you don't show much respect towards me.
	I have worked on HoloCMS for countless hours, I did this for free, without any personal gain for me at all.

	Please respect me and my work, and do not edit or remove the line defined below.

	If I do find people editing that line HoloCMS may go underground or I will simply stop developing, I'm
	prepared to go to the extreme.

	(Also, you're breaking the license if you do, and with that, copyright law)

	If you have any questions regarding this, feel free to e-mail me:
	meth0d at meth0d dot org

	Thanks in advance.

*/

?>

<!--[if lt IE 7]>
<script type="text/javascript">
Pngfix.doPngImageFix();
</script>
<![endif]-->

<div id="footer">
	<p><a href="<?php echo $path; ?>/iot/go?lang=en&country=uk" target="_new">Cont&aacute;ctanos</a> | <a href="<?php echo $path; ?>/help" target="_new">Preguntas frecuentes</a> | <a href="http://www.sulake.com" target="_new">Sulake</a> | <a href="<?php echo $path; ?>/disclaimer.php" target="_new">T&eacute;rminos de servicio</a> | <a href="<?php echo $path; ?>/privacy.php" target="_new">Pol&iacute;tica de privacidad</a> | <a href="<?php echo $path; ?>/community">Publicidad</a></p>
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE BELOW WHATSOEVER! *@@*/ ?>
	<p>Powered by HoloCMS &copy; 2008 Meth0d & Parts by Yifan, sisija.<br/>
Las marcas, copyright y bases de datos del sitio Habbo, as&iacute; como su contenido, son propiedad de Sulake Inc.<br /><strong>HRW Cms, Una Producci&oacute;n <a href="http://habboretroweb.net">HabboRetroWeb</a>  Creado por Victor. Todos los Derechos Reservados.</strong><br />
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE ABOVE WHATSOEVER! *@@*/ ?>
</div>			</div>
        </div>
    </div>
</div>

<script type="text/javascript">
HabboView.run();
</script>

<?php echo $analytics; ?>
</body>
</html>