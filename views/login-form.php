<?php

    require_once __DIR__ . '/inicio.html';

?>

<main class="container">

    <form class="container__formulario" method="post">
        <h2 class="formulario__titulo">Efetue login</h3>
            <div class="formulario__campo">
                <label class="campo__etiqueta" for="usuario">Usuário</label>
                <input name="email" class="campo__escrita" required
                    placeholder="Digite seu usuário" id='email' type="email" />
            </div>


            <div class="formulario__campo">
                <label class="campo__etiqueta" for="password">Senha</label>
                <input type="password" name="password" class="campo__escrita" required placeholder="Digite sua senha"
                    id='password' />
            </div>

            <input class="formulario__botao" type="submit" value="Entrar" />
    </form>

</main>

<?php require_once __DIR__ . '/fim.html';