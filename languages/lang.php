<?php

#Le if() va me permettre de savoir si une session est déjà ouverte ou non et donc, dans les deux cas, m'éviter d'avoir des erreurs.
#Si une session est déjà ouverte, on ignore cette condition et on exécute le reste du code.

if(session_status() === PHP_SESSION_NONE) {
  session_start(); #Si aucune session n'est ouverte, on en ouvre une nouvelle et on exécute le reste du code.
}

require getLanguages();

function getLanguages() {
  
  #Je vérifie si il existe une autre langue (?? / équivalent de isset), si ce n'est pas le cas, je met le français comme langue par défaut.
  $_SESSION['lang'] = $_SESSION['lang'] ?? 'fr';

  #Si l'utilisateur change la langue alors il faut récupérer la langue en question (GET) si l'utilisateur ne change pas la langue alors on remet la langue par défaut.
  $_SESSION['lang'] = $_GET['lang'] ?? $_SESSION['lang'];

  #Enfin, je concatène $_SESSION['lang'] à l'extension .php pour aller chercher le fichier correspondant à la langue sélectionnée. Cela fonctionne même si je rajoute 50 langues.
  return 'languages/'.$_SESSION['lang'].'.php';
}

?>
