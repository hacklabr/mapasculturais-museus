<?php
$entityClass = $entity->getClassName();
$entityName = strtolower(array_slice(explode('\\', $entityClass),-1)[0]);
$areas = array_values($app->getRegisteredTaxonomy($entityClass, 'mus_area')->restrictedTerms);
sort($areas);
?>
<div class="widget">
    <h3>Tipologia de Acervo</h3>
    <?php if($this->isEditable()): ?>
         <span class="js-editable-taxonomy" data-original-title="Tipologia de Acervo" data-emptytext="Selecione pelo menos uma tipologia" data-restrict="true"
         data-taxonomy="mus_area"><?php echo implode('; ', $entity->terms['mus_area'])?></span>
         <span style="display:none" class="js-editable-taxonomy" data-original-title="Área de Atuação" data-taxonomy="area">Museu</span>
    <?php else: ?>
        <?php foreach($areas as $i => $t): if(in_array($t, $entity->terms['mus_area'])): ?>
             <a class="tag tag-<?php echo $this->controller->id ?>" href="<?php echo $app->createUrl('site', 'search') ?>##(<?php echo $entityName ?>:(areas:!(<?php echo $i ?>)),global:(enabled:(<?php echo $entityName ?>:!t),filterEntity:<?php echo $entityName ?>))">
                 <?php echo $t ?>
             </a>
         <?php endif; endforeach; ?>
    <?php endif;?>
</div>