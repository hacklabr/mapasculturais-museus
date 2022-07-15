<?php

namespace SealModelMuseus;

use SealModelTab;



class Plugin extends SealModelTab\SealModelTemplatePlugin
{
    function getModelData(){
        return [
            'label'=> 'Modelo Museus',
            'name' => 'SealModelMuseus',
            'css' => 'model-tab-museus.css',
            'background' => 'modelo_certificado_museus.jpg',
            'preview' => 'preview-model-museus.png'
        ];
    }
}