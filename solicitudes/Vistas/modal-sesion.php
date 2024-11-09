<div class="modal" tabindex="-1" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Aceasoft | Inicio de sesión</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="../../webServices/ws.login.php" method="post" id="frmLogin" caso=2>
        <div class="modal-body">
          <div class="alert alert-danger" role="alert" id="msgUser" style="display: none;">
            Usuario o contraseña incorrecto.
          </div>
          <div class="form-group"><label for="txtLogin">Usuario</label><input type="text" name="username" id="txtLogin" placeholder="Login" class="form-control"></div>
          <div class="form-group"><label for="txtPass">Contraseña</label><input type="password" name="password" id="txtPass" placeholder="Contraseña" class="form-control"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </div>
      </form>
    </div>
  </div>
</div>