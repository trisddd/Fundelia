<?php

require_once "db_connect.php";

#region CREATE
function create_user($first_name, $last_name, $email, $password, $birthdate, $country, $genre, $creation_time)
{
    global $db;
    $query = "INSERT INTO `users`(`first_name`,`last_name`,`email`,`password`,`birthdate`,`country`,`genre`,`creation_time`,`is_verified`) VALUES 
                (:first_name,:last_name,:email,:password,:birthdate,:country,:genre,:creation_time, false);";
    $request = $db->prepare($query);
    $password = password_hash($password, PASSWORD_BCRYPT);
    $request->execute(array(
        ":first_name" => $first_name,
        ":last_name" => $last_name,
        ":email" => $email,
        ":password" => $password,
        ":birthdate" => $birthdate,
        ":country" => $country,
        ":genre" => $genre,
        ":creation_time" => $creation_time
    ));
}

#endregion
#region READ

// Read all
function read_all_users()
{
    global $db;
    $query = "SELECT 
                *
            FROM
                `users`;";
    $request = $db->prepare($query);
    $request->execute();
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

// Read by id
function read_user_by_id($id)
{
    global $db;
    $query = "SELECT 
                *
            FROM
                `users`
            WHERE
                `id`=:id;";
    $request = $db->prepare($query);
    $request->execute(array(
        ":id" => $id
    ));
    return $request->fetch(PDO::FETCH_ASSOC);
}

// Read by email
function read_user_by_email($email)
{
    global $db;
    $query = "SELECT 
                *
            FROM
                `users`
            WHERE
                `email`=:email;";
    $request = $db->prepare($query);
    $request->execute(array(
        ":email" => $email
    ));
    return $request->fetch(PDO::FETCH_ASSOC);
}

function read_user_by_email_reinforced($email, $first_name, $last_name)
{
    global $db;
    $query = "SELECT 
                *
            FROM
                `users`
            WHERE
                `email`=:email
                AND `first_name`=:first_name
                AND `last_name`=:last_name;";
    $request = $db->prepare($query);
    $request->execute(array(
        ":email" => $email,
        ":first_name" => $first_name,
        ":last_name" => $last_name
    ));
    return $request->fetch(PDO::FETCH_ASSOC);
}

// verifier si un mail existe deja 
function email_exists($email)
{
    global $db; // Ton objet PDO

    $query = "SELECT COUNT(*) FROM users WHERE email = :email";
    $request = $db->prepare($query);
    $request->execute([
        ":email" => $email
    ]);

    $count = $request->fetchColumn();

    return $count > 0; // Retourne true si email existe, false sinon
}



// UPDATE
// Insertion d'un utilisateur dans la base de données

/**
 * insert_user_on_first_inscription permet dajouter un utilisatuer dans user avec tous ses attribut et de lui creer aussi une carte   .
 *
 * @param mixed $pass  $first_name, $last_name, $email, $birthdate, $gender, $code Liban numérique à traiter.
 * @return Error sil ya une erreur si non ne retourne rien.
 */


function insert_user_on_first_inscription($pass, $first_name, $last_name, $email, $birth_date, $gender, $code)
{
    global $db;

    try {
        // Début de la transaction
        $db->beginTransaction();

        // Insertion de l'utilisateur
        $hashed_password = password_hash($pass, PASSWORD_BCRYPT);
        $hashed_code = password_hash($code, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (first_name, last_name, email, password, birthdate, genre ,code) 
                  VALUES (:first_name, :last_name, :email, :password, :birthdate, :genre, :code)";
        $request = $db->prepare($query);
        $request->execute([
            ":first_name" => $first_name,
            ":last_name" => $last_name,
            ":email" => $email,
            ":password" => $hashed_password,
            ":birthdate" => $birth_date,
            ":genre" => $gender,
            ":code" => $hashed_code,
        ]);

        $user_id = $db->lastInsertId();
        $query = "SELECT id FROM `accounts` ORDER BY id DESC LIMIT 1";
        $request = $db->query($query);
        $result = $request->fetch();
        if ($result && $result['id'] == false) {
            $last_account_id = 1;
            $account_number = hash_11_Unique($last_account_id);
        } else {
            $last_account_id = $result['id'];
            $account_number = hash_11_Unique($last_account_id + 1);
        }


        // Génération du numéro de compte
        $iban = generate_IBAN_Fundelia($account_number);

        // Création du compte courant
        $query = "INSERT INTO accounts (account_type_id, name, IBAN) 
                  VALUES (:account_type_id, :name, :IBAN)";
        $request = $db->prepare($query);
        $request->execute([
            ":account_type_id" => 1,
            ":name" => "Compte principal",
            ":IBAN" => $iban
        ]);
        $account_id = $db->lastInsertId();

        // Insertion dans owners
        $query = "INSERT INTO owners (user_id, account_id) 
                  VALUES (:user_id, :account_id)";
        $request = $db->prepare($query);
        $request->execute([
            ":user_id" => $user_id,
            ":account_id" => $account_id
        ]);

        // Génération des infos de carte bancaire
        $query = "SELECT id FROM `cards` ORDER BY id DESC LIMIT 1";
        $request = $db->query($query);
        $result = $request->fetch(PDO::FETCH_ASSOC);
        $last_account_id = $result ? $result['id'] + 1 : 1;
        $creation_motif = "Creation de la premiere carte bancaire lors de la cration du compte ";



        $card_number = hash9_From_Input($last_account_id);
        $generated_card_number = generate_Card_Number($card_number);
        $csc = rand(100, 999);
        $expiration_date = date('Y-m-d', strtotime('+3 years'));

        $query = "INSERT INTO cards (user_id, name, card_numbers, account_id, csc, expiration_date, holder_name,creation_reason) 
                  VALUES (:user_id, :name, :card_numbers, :account_id, :csc, :expiration_date,:holder_name,:creation_reason)";
        $request = $db->prepare($query);
        $request->execute([
            ":user_id" => $user_id,
            ":name" => "Carte principale",
            ":card_numbers" => $generated_card_number,
            ":account_id" => $account_id,
            ":csc" => $csc,
            ":expiration_date" => $expiration_date,
            ':holder_name' => $first_name,
            ':creation_reason' => $creation_motif
        ]);
    notification_on_first_inscription($user_id);
        // Si tout est ok, commit la transaction
        $db->commit();
    } catch (Exception $e) {
        // S'il y a une erreur, rollback
        $db->rollBack();
        // Tu peux logger ou afficher l'erreur ici
        throw $e; // Ou gérer autrement selon ton besoin
    }
}
/// fonction qui permet de lister lensemble des cartes lie a un compte utilisateur a besoin de l'id de lutilisatuer et de lid du compte

/**
 * juste afficher tous les cartes dun utilisateur .
 *
 * @param number $user_id, $account_id .
 * @return array 
 */

function show_user_card_link_to_account($user_id, $account_id)
{
    global $db;

    $query = "
SELECT 
    cards.card_numbers,
    cards.card_type,
    cards.CSC,
    cards.expiration_date,
    cards.holder_name,
    cards.name,
    cards.freeze,
    accounts.IBAN,
    accounts.balance
FROM cards
JOIN accounts ON cards.account_id = accounts.id
WHERE cards.user_id = ? AND cards.account_id = ?
";

    $stmt = $db->prepare($query);
    $stmt->execute([$user_id, $account_id]);
    $cartes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $cartes;
}


// Génération des infos de la carte  un compte en particulier 

function create_card_for_account($user_id, $account_id, $card_name, $card_type, $card_holder, $creation_motif)
{
    global $db;

    $query = "SELECT id FROM `cards` ORDER BY id DESC LIMIT 1";
    $request = $db->query($query);
    $result = $request->fetch(PDO::FETCH_ASSOC);
    $last_account_id = $result ? $result['id'] + 1 : 1;


    $numero_decompte = hash9_From_Input($last_account_id); // ou une autre logique
    $card_number = generate_Card_Number($numero_decompte);
    $csc = rand(100, 999);
    $expiration_date = date('Y-m-d', strtotime('+3 years'));

    // Insertion dans la table cards
    $query = "INSERT INTO cards (user_id, name, card_numbers, account_id, csc, expiration_date, card_type, holder_name,creation_reason) 
              VALUES (:user_id, :name, :card_numbers, :account_id, :csc, :expiration_date, :type,:holder_name,:creation_reason )";
    $stmt = $db->prepare($query);
    $stmt->execute([
        // juste en haut c la ligne 43 
        ':user_id' => $user_id,
        ':name' => $card_name,
        ':card_numbers' => $card_number,
        ':account_id' => $account_id,
        ':csc' => $csc,
        ':expiration_date' => $expiration_date,
        ':type' => $card_type,
        ':holder_name' => $card_holder,
        ':creation_reason' => $creation_motif
    ]);
}

// Vérifie le nombre de cartes déjà liées à ce compte

/**
 * juste pour trouver le nombre de carte dont dispose un utilisatuer pour un compte  .
 *
 * @param number $account_id .
 * @return number .
 */
function verify_user_number_of_cards($account_id)
{
    global $db;


    $query = "SELECT COUNT(*) FROM cards WHERE account_id = :account_id";
    $stmt = $db->prepare($query);
    $stmt->execute([':account_id' => $account_id]);
    $nb_cartes = $stmt->fetchColumn();
    return $nb_cartes;
}

//read by card number 

function get_mail_by_card_number($card_number)
{
    global $db;
    $query = "
        SELECT u.email
        FROM users u
        JOIN cards c ON u.id = c.user_id
        WHERE c.card_numbers = :card_number
        LIMIT 1
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([':card_number' => $card_number]);

    $email = $stmt->fetchColumn();

    return $email !== false ? $email : null;
}
///
function get_transaction_history_by_user_id($user_id, $limit)
{
    global $db;

    // Récupérer tous les IBANs des comptes appartenant à l'utilisateur via la table owners
    $ibanQuery = "
        SELECT a.IBAN 
        FROM accounts a
        INNER JOIN owners o ON a.id = o.account_id
        WHERE o.user_id = :user_id
    ";
    $stmt = $db->prepare($ibanQuery);
    $stmt->execute([':user_id' => $user_id]);
    $ibans = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($ibans)) {
        return []; // L'utilisateur n'a pas de compte
    }

    // Construire dynamiquement une clause IN sécurisée
    $placeholders = implode(',', array_fill(0, count($ibans), '?'));
    if ($limit == "") {
        $query = "
        SELECT 
            *
        FROM transactions
        WHERE emitter_IBAN IN ($placeholders)
           OR beneficiary_IBAN IN ($placeholders)
        ORDER BY date DESC
    ";
    } else {
        $query = "
            SELECT
                *
            FROM transactions
            WHERE (emitter_IBAN IN ($placeholders) OR beneficiary_IBAN IN ($placeholders))
              AND transaction_type = 'payment'
            ORDER BY date DESC
            LIMIT $limit
                ";
    }


    $stmt = $db->prepare($query);
    // On envoie deux fois la même liste : pour emitter et beneficiary
    $stmt->execute(array_merge($ibans, $ibans));
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function get_transaction_history_by_user_id_for_dashboard($user_id, $limit)
{
    global $db;

    // Récupérer tous les IBANs des comptes appartenant à l'utilisateur via la table owners
    $ibanQuery = "
        SELECT a.IBAN 
        FROM accounts a
        INNER JOIN owners o ON a.id = o.account_id
        WHERE o.user_id = :user_id
    ";
    $stmt = $db->prepare($ibanQuery);
    $stmt->execute([':user_id' => $user_id]);
    $ibans = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($ibans)) {
        return []; // L'utilisateur n'a pas de compte
    }

    // Construire dynamiquement une clause IN sécurisée
    $placeholders = implode(',', array_fill(0, count($ibans), '?'));

    $query = "
            SELECT
                *
            FROM transactions
            WHERE (emitter_IBAN IN ($placeholders) OR beneficiary_IBAN IN ($placeholders))
            ORDER BY date DESC
            LIMIT $limit
                ";


    $stmt = $db->prepare($query);
    // On envoie deux fois la même liste : pour emitter et beneficiary
    $stmt->execute(array_merge($ibans, $ibans));
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function get_transfert_history_by_user_id($user_id, $limit = 100, $sort_by = 'date-desc')
{
    global $db;

    // Récupérer les IBANs des comptes de l'utilisateur
    $ibanQuery = "
        SELECT a.IBAN 
        FROM accounts a
        INNER JOIN owners o ON a.id = o.account_id
        WHERE o.user_id = :user_id
    ";
    $stmt = $db->prepare($ibanQuery);
    $stmt->execute([':user_id' => $user_id]);
    $ibans = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($ibans)) {
        return [];
    }

    // Sécurisation LIMIT
    $limit = is_numeric($limit) ? (int) $limit : 100;

    // Définir tri SQL selon le paramètre sort_by
    switch ($sort_by) {
        case 'amount-asc':
            $order_by = 'ABS(amount) ASC';
            break;
        case 'amount-desc':
            $order_by = 'ABS(amount) DESC';
            break;
        case 'date-asc':
            $order_by = 'date ASC';
            break;
        case 'date-desc':
        default:
            $order_by = 'date DESC';
            break;
    }

    // Création des placeholders pour IN (?, ?, ?, ...)
    $placeholders = implode(',', array_fill(0, count($ibans), '?'));

    // Requête finale avec tri SQL + limit
    $query = "
    SELECT *
    FROM transactions
    WHERE emitter_IBAN IN ($placeholders) 
       OR beneficiary_IBAN IN ($placeholders)
    ORDER BY $order_by
    LIMIT $limit
";


    $stmt = $db->prepare($query);
    $stmt->execute(array_merge($ibans, $ibans));

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}



function freeze_unfreeze_card($card_number, $state)
{
    global $db;
    if ($state == 1) {
        $query = "
        UPDATE cards
        SET freeze = FALSE
        WHERE card_numbers = :card_number
    ";

    } else {
        $query = "
        UPDATE cards
        SET freeze = TRUE
        WHERE card_numbers = :card_number
    ";
    }


    $stmt = $db->prepare($query);
    $stmt->execute([':card_number' => $card_number]);

    // Vérifie si une ligne a été affectée 
    return $stmt->rowCount() > 0;
}
/**
 * Vérifie si un utilisateur est bien propriétaire d’un compte à partir de l’IBAN,
 * et si son prénom et nom correspondent à ceux fournis.
 *
 * @param string $iban        L’IBAN du compte à vérifier
 * @param string $first_name  Le prénom attendu de l'utilisateur
 * @param string $last_name   Le nom attendu de l'utilisateur
 * @return bool               true si les informations correspondent, false sinon
 */

function verify_user_by_iban($iban, $first_name, $last_name)
{
    global $db;

    // 1. Récupérer l'ID du compte à partir de l'IBAN
    $stmt_account = $db->prepare("SELECT id FROM accounts WHERE IBAN = :iban");
    $stmt_account->execute([':iban' => $iban]);
    $account = $stmt_account->fetch(PDO::FETCH_ASSOC);

    if (!$account) {
        return false; // IBAN introuvable
    }

    // 2. Trouver les utilisateurs liés à ce compte via la table owners
    $stmt_owner = $db->prepare("
        SELECT users.first_name, users.last_name
        FROM owners
        JOIN users ON owners.user_id = users.id
        WHERE owners.account_id = :account_id
    ");
    $stmt_owner->execute([':account_id' => $account['id']]);
    $users = $stmt_owner->fetchAll(PDO::FETCH_ASSOC);

    // 3. Vérifier s'il y a un utilisateur avec le prénom + nom correspondant
    foreach ($users as $user) {
        if (
            strtolower($user['first_name']) === strtolower($first_name) &&
            strtolower($user['last_name']) === strtolower($last_name)
        ) {
            return true;
        }
    }

    return false;
}

function add_beneficiary($user_id, $iban, $first_name, $last_name)
{
    global $db;

    // Vérifie si le bénéficiaire existe déjà pour cet utilisateur
    $check = $db->prepare("SELECT COUNT(*) FROM beneficiaries WHERE user_id = ? AND iban = ?");
    $check->execute([$user_id, $iban]);
    $exists = $check->fetchColumn();

    if ($exists > 0) {
        // Le bénéficiaire existe déjà, on ne fait rien
        return false;
    }

    // Insertion du bénéficiaire
    $stmt = $db->prepare("INSERT INTO beneficiaries (user_id, iban, first_name, last_name) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $iban, $first_name, $last_name]);
    notification_of_beneficiary_adding($user_id,$first_name, $last_name);
    return true;
}

function get_all_beneficiaries_by_user_id($user_id)
{
    global $db;
    $query = "SELECT * FROM beneficiaries WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function read_all_notifications_by_user($user_id)
{
    global $db;
    $query = "SELECT * FROM `notifications` WHERE user_id = ? ORDER BY date DESC;";
    $request = $db->prepare($query);
    $request->execute([$user_id]);
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function read_last_3_notifications_by_user($user_id)
{
    global $db;
    $query = "SELECT * FROM `notifications` WHERE user_id = ? ORDER BY date DESC LIMIT 3;";
    $request = $db->prepare($query);
    $request->execute([$user_id]);
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function mark_notification_as_read($notif_id, $user_id)
{
    global $db;
    $query = "UPDATE `notifications` SET status = TRUE WHERE id = ? AND user_id = ?;";
    $request = $db->prepare($query);
    return $request->execute(
        [$notif_id, $user_id]
    );
}

function count_unread_notifications_by_user($user_id) {
    global $db;
    $query = "SELECT COUNT(*) AS unread_count FROM `notifications` WHERE user_id = ? AND status = FALSE;";
    $request = $db->prepare($query);
    $request->execute([$user_id]);
    $result = $request->fetch(PDO::FETCH_ASSOC);
    return $result['unread_count'] ?? 0;
}

function create_notification($user_id, $title, $message) {
    global $db;
    $query = "INSERT INTO `notifications` (user_id, title, message) VALUES (?, ?, ?);";
    $request = $db->prepare($query);
    return $request->execute([$user_id, $title, $message]);
}


// DELETE
function delete_user($id)
{
    global $db;
    $query = "DELETE FROM
                `users`
            WHERE
                `id` = ?;";
    $request = $db->prepare($query);
    $request->execute([$id]);
}



function delete_card($card_number)
{
    global $db; // Accès à la connexion à la base de données

    $query = "
        DELETE FROM cards
        WHERE card_numbers = :card_number
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([':card_number' => $card_number]);

    // Retourne TRUE si au moins une ligne a été supprimée (carte trouvée et supprimée), FALSE sinon.
    return $stmt->rowCount() > 0;
}


// print_r(read_user_by_email("badcop912@gmail.com")); 


