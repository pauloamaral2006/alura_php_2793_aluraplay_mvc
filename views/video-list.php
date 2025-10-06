<?php

    require_once __DIR__ . '/inicio.html';

?>

<ul class="videos__container" alt="videos alura">
    
    <?php foreach ($videoList as $video) { ?>

        <?php if(str_starts_with($video->url ?? '', 'https')) { ?>
                
            <li class="videos__item">

                <?php if($video->getFilePath() !== null) { ?>

                    <a href="<?= $video->url; ?>">
                        <img class="videos__item-imagem" src="<?= $video->getFilePath() ?>" alt="thumb do vídeo" style="width:100%;">
                    </a>

                <?php } else { ?>

                    <iframe width="100%" height="72%" src="<?= $video->url; ?>"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>

                <?php } ?>

                <div class="descricao-video">
                    <img src="./img/logo.png" alt="logo canal alura">
                    <h3><?= $video->title; ?></h3>
                    <div class="acoes-video">
                        <a href="editar-video?id=<?= $video->id; ?>">Editar</a>
                        <a href="remover-video?id=<?= $video->id; ?>">Excluir</a>
                    </div>
                </div>
            </li>

        <?php } ?>

    <?php } ?>

</ul>

<?php require_once __DIR__ . '/fim.html';
