<script src="http://bobbaworld.fr/web-gallery/static/js/common.js" type="text/javascript"></script>
<html>
     <head><title>UltraUpload - Subida y alojamiento gratuito de im&aacute;genes y fotos, alojamiento de im&aacute;genes</title></head>
     <body>

     <?php
     $poids_max = 512000; // Peso m&aacute;ximo de la imagen en bytes (1Ko = 1024 bytes)
     $repertoire = 'fichiers/'; // Directorio de subida

     if (isset($_FILES['fichier']))
     {

     // Se comprueba el tipo del archivo
     if ($_FILES['fichier']['type'] != 'image/png' && $_FILES['fichier']['type'] != 'image/jpeg' && $_FILES['fichier']['type'] != 'image/jpg' && $_FILES['fichier']['type'] != 'image/gif')
     {
     $erreur = 'El archivo debe estar en formato .jpeg, .gif o .png .';
     }

     // Se comprueba el peso de la imagen
     elseif ($_FILES['fichier']['size'] > $poids_max)
     {
     $erreur = 'La imagen debe ser inferior a ' . $poids_max/1024 . 'Ko.';
     }

     // Se comprueba si el directorio de subida existe
     elseif (!file_exists($repertoire))
     {
     $erreur = 'Error: la carpeta de subida no existe.';
     }

     // Si hay un error se muestra; si no, se puede subir
     if(isset($erreur))
     {
     echo '' . $erreur . '<br><a href="javascript:history.back(1)">Volver</a>';
     }
     else
     {

     // Se define la extensi&oacute;n del archivo y se nombra con el timestamp actual
     if ($_FILES['fichier']['type'] == 'image/jpeg') { $extention = '.jpeg'; }
     if ($_FILES['fichier']['type'] == 'image/jpeg') { $extention = '.jpg'; }
     if ($_FILES['fichier']['type'] == 'image/png') { $extention = '.png'; }
     if ($_FILES['fichier']['type'] == 'image/gif') { $extention = '.gif'; }
     $nom_fichier = time().$extention;

     // Se sube el archivo al servidor.
     if (move_uploaded_file($_FILES['fichier']['tmp_name'], $repertoire.$nom_fichier))
     {
     $url = 'http://kiiwi.power-heberg.com/test/'.$repertoire.''.$nom_fichier.'';
     echo '&iexcl;Tu imagen se ha subido al servidor con &eacute;xito!<br>Aqu&iacute; tienes el enlace: <input type="text" value="' . $url . '" size="60">';
     }
     else
     {
     echo 'No se ha podido subir la imagen al servidor.';
     }

     }

     }
     else
     {
     ?>
     <form method="post" enctype="multipart/form-data">
     <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $poids_max; ?>">
     <input type="file" name="fichier">
     <input type="submit" value="Enviar">
     </form>
     <?php
     }
?>
