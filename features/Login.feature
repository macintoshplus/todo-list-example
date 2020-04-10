#language: fr
Fonctionnalité: Connexion à l'application

  Contexte:
    Etant donné que l'utilisateur "todo@me.fr" est enregsitré avec le mot de passe "MonSuperPassWord"

  Scénario: Connexion
    Etant donné que je suis sur la page de connexion
    Lorsque je me connecte en tant que "todo@me.fr" avec le mot de passe "MonSuperPassWord"
    Alors je dois être sur la page de double authentification
    Lorsque je saisi le code de double authentification
    Alors je dois être sur la liste des todo listes
