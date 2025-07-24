# Bienvenue sur le repository du projet Fundelia
## Projet de S2 M25 - Kevin A., Ulysse A., Tristan D.

Ce projet a été réalisé dans le cadre du second semestre de notre formation. Nous étions en Bac+1 à ce moment, c'est pourquoi ce projet - étant quand bien même une fierté pour nous tous - est loin d'être parfait.
Si vous constatez des problèmes évidents / failles de sécurité, je serais ravi de pouvoir en discuter avec vous afin d'en apprendre plus ! Toute leçon est bonne à prendre 😉

## Ressources externes à installer pour le bon fonctionnement du site :
* Stack LAMPP ou XAMPP (XAMPP serait le mieux)
* PHPMailer
  

## Avant de lancer penser à :
* ### Si l'on est en mode développeur avec fixtures (dev.fundelia) :
  * Lancer fundelia.sql dans PHPMyAdmin
* ### Si l'on est en mode déploiement (fundelia) :
  * Lancer fundelia_db_without_fixtures.sql dans PHPMyAdmin

## Afin que vous n'ayez rien à faire de plus avant de lancer (et nous sommes conscients qu'il s'agit normalement d'une faille de sécurité importante), nous vous fournissons un fichier env.php contenant :
* Les identifiants (de base) de PHPMyAdmin
* La clé privée de la banque utilisée pour les paiements

## ‼️Vous avez tout de même à fournir dans le fichier env.php :
* Un email valide ($bank_email)
* Le mot de passe de l'email ($mail_password)

Une fois que tout est prêt, vous êtes libre de visiter ! 

-> http://localhost/fundelia <-


Note : Pour tester les paiements, vous pouvez rentrer l'url http://localhost/fundelia/fruit_corporation/controller
