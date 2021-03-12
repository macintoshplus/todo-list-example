#language: fr
@register
Fonctionnalité: Enregistrement d'un utilisateur
  Contexte:
    Etant donné que je suis sur la page de connexion
    Et que je clique sur le lien "Sign in" du menu
    Alors je dois être sur la page d'enregistrement d'un utilisateur

  Scénario: Enregistrement d'un utilisateur
    Lorsque je saisi l'adresse courriel "me@test.com" avec le mot de passe "Kiwi@365" et que je valide
    Alors je dois être sur la page de login

  Scénario: Mot de passe ayant fuité
    Lorsque je saisi l'adresse courriel "me@test.com" avec le mot de passe "test" et que je valide
    Alors je dois voir l'erreur "This password has been leaked in a data breach, it must not be used. Please use another password." pour le mot de passe

  Scénario: Utilisateur déjà enregistré
    Etant donné que l'utilisateur "test@test.fr" est enregistré avec le mot de passe "Kiwi@365"
    Lorsque je saisi l'adresse courriel "test@test.fr" avec le mot de passe "Kiwi@365" et que je valide
    Alors je dois voir l'erreur "This value is already used." pour le courriel

  Scénario: Utilisateur et mot de passe identique
    Lorsque je saisi l'adresse courriel "me@test.com" avec le mot de passe "me@test.com" et que je valide
    Alors je dois voir l'erreur "Your password should not be the same as your email." pour le mot de passe
