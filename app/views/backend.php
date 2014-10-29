<!DOCTYPE html>
<html lang="fr">
<head>

  <meta charset="UTF-8">

  <title>Subbly Backend GUI</title>
  <script src="<?= URL::to('/themes/backend/assets/lib/modernizr.js'); ?>"></script>

  <link rel="stylesheet" href="<?= URL::to('/themes/backend/assets/css/subbly.development.css'); ?>">
  <style>
  </style>

</head>
<body>
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
      <div class="view-full" style="background:#fff"> full view</div>
    </section><!-- /.main-view -->
  </div><!-- /. container -->
</body>
</html>
