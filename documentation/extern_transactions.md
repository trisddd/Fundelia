# Protocole de contact interbancaire

Nous sommes dans le cas dans payment_api où le BIN de cardNum n'est pas celui de la banque du Business.
- **Client** possède `cardNum`
- **Banque Business** possède `cardNum` et l'url d'appel **Banque 2**
- **Banque Client** possède `compte client`
- `JSON1 = { cardNum, montant et compte de Business }`
- `JSON2 = { cardNum, montant et IBAN de Business }`

``` mermaid
sequenceDiagram
    participant CM as Client Mail
    participant C as Client
    participant SB as Serveur Business
    participant B1 as Banque Business
    participant B2 as Banque Client


    SB->>B1 : Envoie les informations de paiement dans le JSON1
    Note over B1 : cardNum avec le code BIN de Banque 2 (dans son répertoire)
    opt HandShake
        B1->>B2: envoie h(K1+K2)
        Note over B2 : h1 == h2 ?
        B2 -->> B1 : Si faux : 403 😡
        B2 -->> B1 : Sinon retourne h3,  Token, t1 😀
        Note over B1 : h3 == h(h(K1+K2)+h2) ?
        Note over B1 : Si vrai : Stock Token et t1
        B1 ->> B2 : Handshake End
    end
    opt Transaction
        B1->>B2 : envoie Token dans le Authorization Header <br/> et JSON2 en body
        B2-->>B1 : Si Transaction Invalide : 403 😡
        Note over B2 : Si cardNum pas dans Banque : <br>retourne error
        B2-->>B1 : Sinon cardNum reconnu : <br>Transaction débute 😀
        B1->>SB : Redirect 303 : Other <br/> vers la page de la banque Client
        SB->>C : Redirect 303 : Other <br/> vers la page de la banque Client
        C->>B2 : Requête la page de la banque de confirmation de paiement.
        Note over B2 : Génère le code
        B2->>CM : 📧 mail avec le code
        B2->>C : Retourne le formulaire de saisie du code
        C-->>B2 : Répond avec le code
        B2-->>C : Si Code Invalide : 403 😡
        B2->>B1 : Si Code Valide : transaction complétée ! 🥳<br/> Retourne le check
        Note over B1 : Valide la transaction en BDD
        B1->>B2 : Transaction prise en compte, <br /> Retourne le check
        Note over B2 : Valide la transaction en BDD
        B1->>SB : Transaction validée, <br /> Retourne l'url de redirection "transaction validée"
    end
    SB->>C : Redirect 303 : Other <br/> Confirmation que votre commande a bien été effectuée
```

Évidemment, tout ces échanges ont lieux via HTTPS, les Body HTTP étant chiffrés.
