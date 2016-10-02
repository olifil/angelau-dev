<div class="wrap">
  <h1>Le code de la page d'aministration du plugins</h1>
  <form class="" action="" method="post">
    <p>
      <input type="text" name="login" placeholder="Login" value="<?php if(isset($_GET['login'])){ echo $_GET['login']; } ?>"/>
    </p>
    <p>
      <input type="password" name="pass" placeholder="Mot de passe" />
    </p>
    <p>
      <button type="submit" name="ok" class="btn btn-default">Sauvegarder</button>
    </p>
  </form>
</div>
