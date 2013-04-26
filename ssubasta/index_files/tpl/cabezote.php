<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>SSubasta231231</title>

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
      }

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 95%;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }
    </style>
    <link href="css/css.css" rel="stylesheet" /> 
    <script src="js/general.js"></script>
<!--    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap-responsive.css" rel="stylesheet"> -->

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="http://twitter.github.com/bootstrap/assets/ico/favicon.png">
  <script src="chrome-extension://jmfkcklnlgedgbglfkkgedjfmejoahla/content/flyover.js"></script><script src="chrome-extension://lifbcibllhkdhoafpjfnlhfpfgnpldfl/document_iterator.js"></script><script src="chrome-extension://lifbcibllhkdhoafpjfnlhfpfgnpldfl/find_proxy.js"></script><script src="chrome-extension://lifbcibllhkdhoafpjfnlhfpfgnpldfl/get_html_text.js"></script><script src="chrome-extension://lifbcibllhkdhoafpjfnlhfpfgnpldfl/global_constants.js"></script><script src="chrome-extension://lifbcibllhkdhoafpjfnlhfpfgnpldfl/name_injection_builder.js"></script><script src="chrome-extension://lifbcibllhkdhoafpjfnlhfpfgnpldfl/number_injection_builder.js"></script><script src="chrome-extension://lifbcibllhkdhoafpjfnlhfpfgnpldfl/menu_injection_builder.js"></script><script src="chrome-extension://lifbcibllhkdhoafpjfnlhfpfgnpldfl/string_finder.js"></script><script src="chrome-extension://lifbcibllhkdhoafpjfnlhfpfgnpldfl/change_sink.js"></script><meta name="document_iterator.js"><meta name="find_proxy.js"><meta name="get_html_text.js"><meta name="global_constants.js"><meta name="name_injection_builder.js"><meta name="number_injection_builder.js"><meta name="menu_injection_builder.js"><meta name="string_finder.js"><meta name="change_sink.js"></head>

  <body>

    <div class="container-narrow">
      <div>
      <h2 class="muted">SSubasta</h2>
      <?php if (!$unSesion->obtenerVariable('usuario')) : ?>
        <a class="btn btn-primary btn-mini pull-right"href="login.php">Iniciar Sesión</a>
   		<br>
       <!-- <a style="font-size:8px; color:#0000FF" href="registro.php" class="pull-right">No estás registrado?</a> -->
       <a style="font-size:8px; color:#0000FF" href="../index.php/component/users/?view=registration" class="pull-right">No estás registrado?</a>
      <?php else : ?>  
        <a class="btn btn-primary btn-mini pull-right"href="login.php?cerrar=true">Cerrar Sesión</a>
      <?php endif; ?>    
      </div>