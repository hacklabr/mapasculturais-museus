<?php

namespace SealModelMuseusSBM;

use SealModelTab;



class Plugin extends SealModelTab\SealModelTemplatePlugin
{
    function getModelData(){
        return [
            'label'=> 'Modelo Museus (SBM)',
            'name' => 'SealModelMuseusSBM',
            'css' => 'model-tab-museus-sbm.css',
            'background' => 'modelo_certificado_museus_sbm.jpg',
            'preview' => 'preview-model-museus-sbm.png'
        ];
    }
}