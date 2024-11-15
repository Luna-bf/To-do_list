<?php

#Le if() va me permettre de savoir si une session est déjà ouverte ou non et donc, dans les deux cas, m'éviter d'avoir des erreurs.
#Si une session est déjà ouverte, on ignore cette condition et on exécute le reste du code.

if(session_statut() === PHP_SESSION_NONE) {
  session_start(); #Si aucune session n'est ouverte, on en ouvre une nouvelle et on exécute le reste du code.
}

?>
