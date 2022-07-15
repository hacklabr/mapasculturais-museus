<?php if ( $this->isEditable() || $entity->longDescription ): ?>
    <h3>Descrição</h3>
    <span class="descricao js-editable" data-edit="longDescription" data-original-title="Descrição <?php $this->dict('entities: of the Space') ?>" data-emptytext="Insira uma descrição <?php $this->dict('entities: of the space') ?>" ><?php echo $this->isEditable() ? $entity->longDescription : nl2br($entity->longDescription); ?></span>
<?php endif; ?>
