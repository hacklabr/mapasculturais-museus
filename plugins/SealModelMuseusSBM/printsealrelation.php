<div id="certificate-model-museus-sbm">
    <img class ="cert-background" src="<?php $view->asset('img/modelo_certificado_museus_sbm.jpg') ?>"/>
    <p class="certificate-content"><?php echo nl2br($msg) ?></p>
    <div class="footer">
        <div class="entity-url">
            <a  href="<?php echo $relation->owner->getSingleUrl(); ?>"
                title="<?php echo $relation->owner->name ?>"><?php echo $relation->owner->getSingleUrl(); ?></a>
        </div>
        <div class="footer-signatures">
        </div>
    </div>
</div>
