<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="HoteldeTransito">
    <meta name="keywords" content="hoteldetransito, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="/Trae/public/img/icons/icon-48x48.png" />
    <link href="/Trae/public/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <!-- Menu de navegação -->
        <?php include ROOT_PATH . '/app/views/dashboard/estrutura/menu_admin.php' ?>

        <div class="main">
            <!-- Header (removido o duplicado) -->
            <?php include ROOT_PATH . '/app/views/dashboard/estrutura/header.php' ?>

            <!-- Na seção main, antes de incluir o $formContent -->
            <main class="content">
                <div class="container-fluid p-0">
                    <!-- Mensagens de feedback -->
                    <?php if (isset($mensagem) && !empty($mensagem)): ?>
                        <div class="alert alert-<?php echo $tipoMensagem; ?> alert-dismissible fade show" role="alert">
                            <?php echo $mensagem; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <?php
                        if (isset($formContent) && file_exists($formContent)) {
                            include $formContent;
                        } else {
                            echo "Selecione uma opção no menu lateral.";
                        }
                        ?>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <?php include ROOT_PATH . '/app/views/dashboard/estrutura/footer.php' ?>
        </div>
    </div>

    <script src="/Trae/public/js/app.js"></script>

</body>

</html>