<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Hotel de Trânsito</title>
    <link href="/Hotel/public/css/app.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>
<body>
    <?php
    // Debug temporário
    error_log('Método da requisição: ' . $_SERVER['REQUEST_METHOD']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log('Dados POST recebidos: ' . print_r($_POST, true));
    }
    ?>
    <main class="d-flex w-100 h-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center mt-4">
                                    <h1 class="h2">Bem-vindo</h1>
                                    <p class="lead">Faça login para continuar</p>
                                </div>
                                <div class="m-sm-4">
                                    <form action="/Hotel/login" method="post">
                                        <div class="mb-3">
                                            <label class="form-label">CPF</label>
                                            <input class="form-control form-control-lg" type="text" name="cpf" id="cpf" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Senha</label>
                                            <input class="form-control form-control-lg" type="password" name="senha" required />
                                        </div>
                                        <div>
                                        <?php
                                        if (isset($_GET['error'])) {
                                            echo '<div style="color: red;">Erro no login</div>';
                                        }
                                        ?>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary">Entrar</button>
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            $('#cpf').mask('000.000.000-00');
            
            $('form').on('submit', function(e) {
                // Remove a máscara antes de enviar
                var cpf = $('#cpf').val();
                cpf = cpf.replace(/[^\d]/g, '');
                $('#cpf').val(cpf);
                return true;
            });
        });
    </script>
</body>
</html>