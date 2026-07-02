<?php
// Cette page est une redirection vers le téléchargement du PV
// Le contenu est géré par le contrôleur
header('Location: /etudiant/telecharger-pv/' . ($_GET['id'] ?? 0));
exit;
?>