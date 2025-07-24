# Protocole de Payement minimaliste v1.2

- **Client** possède `cardNum`
- **Business** possède la clef `K2` et `h1 = h(K1+K2)`
- **Banque** possède la clef `K1`, `K2`, `h2 = h(K1+K2)`, `cardNum` et l'url de redirection de **Business** (ouais la banque a beaucoup de trucs)
- `h3 = h(h(K1+K2)+K2)`
- `Token = alea()` : TODO : devrait embarquer le timestamp ?
- `t1 = timestamp()`
- `JSON = { cardNum, montant et compte de Business }`

``` mermaid
---
id: 4decfe08-50ec-4eb1-86ee-10d5eb8ff83d
---
sequenceDiagram
    participant CM as Client Mail
    participant C as Client
    participant GB as Gestionnaire Business
    participant SB as Serveur Business
    participant SK as Serveur Banque

    opt KeyExchange
        Note over SK : Possède K1
        GB ->> SK : connexion via id/mdp <br/> accès à la page de création de clef
        Note over SK : Génère K2 et h1
        SK ->> GB : Renvoie K2 et h1
    end

    Note over GB : Place K2 et h1 sur SB


    C->>SB : Déclenche paiement avec cardNum
    opt HandShake
        SB->>SK: envoie h(K1+K2)
        Note over SK : h1 == h2 ?
        SK -->> SB : Si faux : 403 😡
        SK -->> SB : Sinon retourne h3,  Token, t1 😀
        Note over SB : h3 == h(h(K1+K2)+h2) ?
        Note over SB : Si vrai : Stock Token et t1
        SB ->> SK : Handshake End
    end
    opt Transaction
        SB->>SK : envoie Token dans le Authorization Header <br/> et JSON en body
        SK-->>SB : Si Transaction Invalide : 403 😡
        Note over SK : Si cardNum pas dans Banque : <br>retourne le lien de redirection
        SK-->>SB : Sinon cardNum reconnu : <br>Transaction débute 😀
        SB->>C : Redirect 303 : Other <br/> vers la page de la banque
        C->>SK : Requête la page de la banque de confirmation de paiement.
        Note over SK : Génère le code
        SK->>CM : 📧 mail avec le code
        SK->>C : Retourne le formulaire de saisie du code
        C-->>SK : Répond avec le code
        SK-->>C : Si Code Invalide : 403 😡
        SK-->>C : Si Code Valide : transaction complétée ! 🥳<br/> Redirection dans 3.2.1...
        Note over SK : Valide la transaction en BDD
        C->>SK : Requête Auto pour clôturer la séquence
        SK->>C :  Redirect 303 : Other <br/> vers la page du business
        C->>SB : Requête la confirmation du business
    end
    SB->>C : Confirmation que votre commande a bien été effectuée
```

Évidemment, tout ces échanges ont lieux via HTTPS, les Body HTTP étant chiffrés.
