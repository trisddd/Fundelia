<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 2rem;
    background-color: #f5f7fa;
    color: #333;
  }

  h2 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 2rem;
  }

  form {
    max-width: 450px;
    margin: auto;
    padding: 2rem;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  label {
    display: block;
    margin-top: 1.2rem;
    font-weight: 600;
    color: #34495e;
  }

  input, select {
    width: 100%;
    padding: 10px;
    margin-top: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }

  input:focus, select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
    outline: none;
  }

  button {
    margin-top: 2rem;
    padding: 12px;
    width: 100%;
    font-size: 1rem;
    font-weight: bold;
    color: white;
    background-color: #007bff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  button:hover {
    background-color: #0056b3;
  }
</style>

<h2>Créer un nouveau compte</h2>

<form method="POST" action="/create_business_account">
  <label for="account_type">Type de compte :</label>
    <select name="account_type" id="account_type" required>
      <option value="1">Compte courant</option>
    </select>
    <label for="account_name">Nom du compte :</label>
    <input type="text" name="account_name" id="account_name" required>
    <input id="submit" type="submit" value="Créer le compte">
</form>