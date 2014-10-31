<!DOCTYPE html>
<html lang="fr">
<head>

  <meta charset="UTF-8">

  <title>Subbly Backend GUI</title>

  <link rel="stylesheet" href="<?= URL::to('/themes/backend/assets/css/subbly.development.css'); ?>">

  <script src="<?= URL::to('/themes/backend/assets/lib/modernizr.js'); ?>"></script>
  <script src="<?= URL::to('/themes/backend/assets/lib/pace.min.js'); ?>"></script>

</head>
<body>
  <div class="login -active -logged" id="login">
    <div class="login-contenair">
      <img class src="<?= URL::to('/themes/backend/assets/img/logo.svg'); ?>" width="100" alt="logo Subbly">
      <form action="/void" class="login-box">
        <p class="login-msg">
          Say “Hello shop”
        </p>
        <div class="form-row">
          <div class="form-field">
            <input type="text" class="form-input" placeholder="Email">
          </div><!-- /.form-field -->
        </div><!-- /.form-row -->
        <div class="form-row">
          <div class="form-field">
            <input type="password" class="form-input" placeholder="Password">
          </div><!-- /.form-field -->
        </div><!-- /.form-row -->
        <p class="btn-login">
          <button type="submit" class="btn">
            Login in
          </button>
        </p>
        <p>
          <a href="javascript:;">
            Forget password
          </a>
        </p>
      </form><!-- /.login-box -->
    </div><!-- /.login-contenair -->
  </div><!-- /.login -->
  <div class="container">
    <section class="main-nav">
      <a href="javascript:;" class="main-logo js-trigger-go-home">
         <img class src="<?= URL::to('/themes/backend/assets/img/subbly-logo.svg'); ?>" width="100" alt="logo Subbly">
      </a>

      <div class="user-nav">
        <div class="user-nav-container">
          <a href="javascript:;">
            Le col de Claudine
          </a>
          <span class="caret"></span>
          <ul class="user-nav-sub">
            <li>
              <a href="javascript:;">
                Visit my store
              </a>
            </li>
            <li>
              <a href="javascript:;">
                logout
              </a>
            </li>
          </ul><!-- /.user-nav-sub -->
        </div><!-- /.user-nav-container -->
      </div><!-- /.user-nav -->
      <hr>
      <ul class="bo-nav">
        <li>
          <a href="javascript:;" class="fst-nav js-trigger-go-home">
            Dashboard
          </a>
        </li>
        <li>
          <a href="javascript:;" class="fst-nav js-trigger-go-orders active">
            Orders
          </a>
          <span class="badge product">9</span>
        </li>
        <li>
          <a href="javascript:;" class="fst-nav js-trigger-go-customers">
            Customers
          </a>
        </li>
        <li>
          <a href="javascript:;" class="fst-nav js-trigger-go-products">
            Products
          </a>
        </li>
        <li>
          <a href="javascript:;" class="fst-nav js-trigger-go-settings">
            Settings
          </a>
        </li>
        <li class="fst-nav js-trigger-go-hangard">
          <a href="javascript:;">
            Hangar
          </a>
        </li>
      </ul><!-- /.subbly-nav -->
    </section><!-- /.main-nav -->
    <section class="main-view">
      <div class="view-full"> full view</div>
    </section><!-- /.main-view -->
  </div><!-- /. container -->

  <!-- Force 3d acceleration always and forever :) -->
  <div style="-webkit-transform: translateZ(0)"></div>
  <script src="<?= URL::to('/themes/backend/assets/js/subbly.development.js') ?>"></script>
</body>
</html>
