<?php

namespace MapasMuseus;

use BaseMinc;
use MapasCulturais\App;

class Theme extends BaseMinc\Theme {

    protected static function _getTexts() {
        return array(
            'site: name' => 'Museus',
            'site: in the region' => 'SNB',
            'site: of the region' => 'SNB',
            'site: owner' => 'SNB',
            'site: by the site owner' => 'pelo Ministério da Cultura',
            'home: abbreviation' => "SNB",
            'home: title' => "Bem-vind@!",
//            'home: colabore' => "Colabore com o Mapas Culturais",
            'home: welcome' => "Bem-vindo ao <strong>Museus BR</strong> - a maior plataforma de informações sobre os museus existentes no Brasil.<br><br>

Você vai descobrir que nosso país é muito rico e que tem museus para todos os gostos e interesses. Em Museus BR você encontrará Museus de Arte, de História, de Ciências, de Antropologia, Museus Comunitários, Museus de Território, Museus das mais variadas temáticas e muitos outros que você sequer imagina.<br><br>

Aqui você verificará onde estão localizados os museus do seu estado, do seu município ou de qualquer outro lugar de seu interesse, encontrando os dados de contato e serviços oferecidos como: visitas guiadas, acessibilidade, bibliotecas, arquivos, atendimento a visitantes estrangeiros e muito mais.<br><br>

Seja qual for a pesquisa, a Plataforma Museus BR permitirá extrair os dados dos museus em formato de planilha, por meio de filtros e cruzamentos. <br><br>

Como a Plataforma é colaborativa, você também pode participar, indicando um museu que você conheça e que ainda não faça parte da Museus BR, ou atualizando alguma informação. Basta seguir os passos na seção Como Participar. <br><br>

Aventure-se!<br>
Participe!<br>
Descubra o Brasil por meio dos seus museus!<br>

<p style='text-align:right'>Rede Nacional de Identificação de Museus</p>",
//            'home: events' => "Você pode pesquisar eventos culturais nos campos de busca combinada. Como usuário cadastrado, você pode incluir seus eventos na plataforma e divulgá-los gratuitamente.",
//            'home: agents' => "Você pode colaborar na gestão da cultura com suas próprias informações, preenchendo seu perfil de agente cultural. Neste espaço, estão registrados artistas, gestores e produtores; uma rede de atores envolvidos na cena cultural paulistana. Você pode cadastrar um ou mais agentes (grupos, coletivos, bandas instituições, empresas, etc.), além de associar ao seu perfil eventos e espaços culturais com divulgação gratuita.",
            'home: spaces' => "Procure os museus incluídos na plataforma, acessando os campos de busca combinado que ajudam na precisão de sua pesqusia.",
//            'home: projects' => "Reúne projetos culturais ou agrupa eventos de todos os tipos. Neste espaço, você encontra leis de fomento, mostras, convocatórias e editais criados, além de diversas iniciativas cadastradas pelos usuários da plataforma. Cadastre-se e divulgue seus projetos.",
//            'home: home_devs' => 'Existem algumas maneiras de desenvolvedores interagirem com o Mapas Culturais. A primeira é através da nossa <a href="https://github.com/hacklabr/mapasculturais/blob/master/doc/api.md" target="_blank">API</a>. Com ela você pode acessar os dados públicos no nosso banco de dados e utilizá-los para desenvolver aplicações externas. Além disso, o Mapas Culturais é construído a partir do sofware livre <a href="http://institutotim.org.br/project/mapas-culturais/" target="_blank">Mapas Culturais</a>, criado em parceria com o <a href="http://institutotim.org.br" target="_blank">Instituto TIM</a>, e você pode contribuir para o seu desenvolvimento através do <a href="https://github.com/hacklabr/mapasculturais/" target="_blank">GitHub</a>.',
//
//            'search: verified results' => 'Resultados Verificados',
//            'search: verified' => "Verificados"
            
                        
            'entities: Spaces of the agent'=> 'Museus do agente',
            'entities: Space Description'=> 'Descrição do Museu',
            'entities: My Spaces'=> 'Meus Museus',
            'entities: My spaces'=> 'Meus museus',
            
            'entities: no registered spaces'=> 'nenhum museu cadastrado',
            'entities: no spaces'=> 'nenhum museu',
            
            'entities: Space' => 'Museu',
            'entities: Spaces' => 'Museus',
            'entities: space' => 'museu',
            'entities: spaces' => 'museus',
            'entities: parent space' => 'museu mãe',
            'entities: a space' => 'um museu',
            'entities: the space' => 'o museu',
            'entities: of the space' => 'do museu',
            'entities: In this space' => 'Neste museu',
            'entities: in this space' => 'neste museu',
            'entities: registered spaces' => 'museus cadastrados',
            'entities: new space' => 'novo museu',
        );
    }

    function mus_getSpaceTypes () {
        return json_decode('[
            {"id": 0, "name": "Público Federal",   "typeId": 60, "esfera": "EQ(Pública)", "esfera_tipo": "EQ(Federal)"},
            {"id": 1, "name": "Público Estadual",  "typeId": 60, "esfera": "EQ(Pública)", "esfera_tipo": "EQ(Estadual)"},
            {"id": 2, "name": "Público Distrital", "typeId": 60, "esfera": "EQ(Pública)", "esfera_tipo": "EQ(Municipal)"},
            {"id": 3, "name": "Privado",           "typeId": 61, "esfera": "EQ(Privada)", "esfera_tipo": "NULL()"},
            {"id": 4, "name": "Outro",             "typeId": 61, "esfera": "NULL()",      "esfera_tipo": "EQ(Outra)"},
            {"id": 5, "name": "Não Informado",     "typeId": 61, "esfera": "NULL()",      "esfera_tipo": "NULL()"}
        ]');
    }

    protected function _init() {
        $app = App::i();

        // Adds Museus Space Types to JS
        $this->jsObject['mus_spaceTypes'] = $this->mus_getSpaceTypes();

        /*
         *  Modifica a consulta da API de espaços para só retornar Museus
         * 
         * @see protectec/application/conf/space-types.php
         */
        $app->hook('API.<<*>>(space).query', function(&$data, &$select_properties, &$dql_joins, &$dql_where) {
            $dql_where .= ' AND e._type >= 60 AND e._type <= 69';
        });

        parent::_init();
        
        // BUSCA POR CÓDIGO DO MUSEU
        // adiciona o join do metadado
        $app->hook('repo(<<*>>).getIdsByKeywordDQL.join', function(&$joins, $keyword) {
            $joins .= "
                LEFT JOIN 
                        e.__metadata mus_cod
                WITH 
                        mus_cod.key = 'mus_cod'";
        });

        // filtra pelo valor do keyword
        $app->hook('repo(<<*>>).getIdsByKeywordDQL.where', function(&$where, $keyword) {
            $where .= "OR lower(mus_cod.value) LIKE lower(:keyword)";
        });
        
        
        // modificações nos templates
        $app->hook('template(space.<<*>>.num-sniic):before', function(){
            $entity = $this->data->entity;
            if($entity->mus_cod){
                echo "<small><span class='label'>Código:</span> {$entity->mus_cod}</small>";
            }
        });

        $app->hook('template(space.<<create|edit|single>>.tabs):end', function(){
            $this->part('tabs-museu', ['entity' => $this->data->entity]);
        });

        $app->hook('template(space.<<create|edit|single>>.tabs-content):end', function(){
            $this->part('tab-publico', ['entity' => $this->data->entity]);
            $this->part('tab-mais', ['entity' => $this->data->entity]);
        });
        
        $app->hook('template(space.<<create|edit|single>>.tab-about-service):begin', function(){
            $this->part('about-servive-begin', ['entity' => $this->data->entity]);
        });

        $app->hook('template(space.<<create|edit|single>>.acessibilidade):after', function(){
            $entity = $this->data->entity;
            ?>
        <?php if($this->isEditable() || $entity->mus_acessibilidade_visual): ?>
        <p>
            <span class="label">Acessibilidade para pessoas com deficiências auditivas e visuais: </span>
            <editable-multiselect entity-property="mus_acessibilidade_visual" empty-label="Selecione" allow-other="true" box-title="Acessibilidade para pessoas com deficiências auditivas e visuais" help-text="O museu oferece instalações e serviços destinados às pessoas com deficiências auditivas e visuais?"></editable-multiselect>
        </p>
        <?php endif; ?>
            <?php
        });
        
        $app->hook('template(space.<<*>>.location):after', function(){
            $this->enqueueScript('app', 'endereco-correspondencia', 'js/endereco-correspondencia.js');
            $this->part('endereco-correspondencia', ['entity' => $this->data->entity]);
        });


        // own
        $app->hook('POST(space.own)', function() use($app){
            $this->requireAuthentication();

            $entity = $this->getRequestedEntity();
            if($entity->mus_owned){
                throw new \MapasCulturais\Exceptions\PermissionDenied($app->user, $entity, 'own');
            }
            $app->disableAccessControl();
            $entity->mus_owned = true;
            $entity->owner = $app->user->profile;
            $entity->save(true);
            $app->enableAccessControl();

            $this->json(true);
        });

        $app->hook('template(space.single.header-image):after', function(){
            $this->enqueueScript('app', 'botao-meu-museu', 'js/botao-meu-museu.js');
            $this->part('botao-meu-museu', ['entity' => $this->data->entity]);
        });

        $app->hook('view.render(space/<<*>>):before', function() {
            $this->addTaxonoyTermsToJs('mus_area');
            $this->includeOpeningTimeAssets();
        });

        /*
        $app->hook('template(space.<<create|edit|single>>.acessibilidade):after', function(){
            $this->part('acessibilidade', ['entity' => $this->data->entity]);
        });
        */
    }

    static function getThemeFolder() {
        return __DIR__;
    }

    public function getMetadataPrefix() {
        return 'mus_';
    }

    protected function _getAgentMetadata() {
        return [];
    }

    protected function _getEventMetadata() {
        return [];
    }
    
    protected function _getProjectMetadata() {
        return [];
    }

    protected function _getSpaceMetadata() {
        return [
            'verificado' => [
                'label' => 'O Museu é verificado?',
                'type' => 'boolean'
            ],

            'owned' => [
                'label' => 'Se o museu já apropriado por algum usuário'
            ],
            
            'cod' => [
                'label' => 'Número na Processada',
                'type' => 'readonly'
            ],
            
            'instituicaoMantenedora' => [
                'label' => 'Instituição mantenedora'
            ],
            
            'instumentoCriacao_tipo' => [
                'label' => 'Instrumento de criação',
                'type' => 'select',
                'allowOther' => true,
                'allowOtherText' => 'Outro',
                'options' => [
                    '' => 'Não possui',
                    'Lei',
                    'Decreto-Lei',
                    'Decreto',
                    'Portaria',
                    'Resolução',
                    'Ata de Reunião'
                ]
            ],
            
            'instumentoCriacao_descricao' => [
                'label' => 'Descrição do instrumento de criação',
            ],
            
            'status' => [
                'label' => 'Status do Museu',
                'type' => 'select',
                'options' => [
                    'aberto' => 'Aberto',
                    'fechado' => 'Fechado',
                    'implantacao' => 'Em implantação'
                ]
            ],
            
            'abertura_ano' => [
                'label' => 'Ano de abertura',
                'type' => 'int',
                'validations' => [
                    'v::intVal()' => 'O ano de abertura deve ser um valor numérico inteiro'
                ]
            ],
            
            'abertura_publico' => [
                'label' => 'Tipo de públio ao qual o museu é aberto',
                'type' => 'select',
                'options' => [
                    'Para público em geral',
                    'SOMENTE para públicos específicos'
                ]
            ],
            
            'itinerante' => [
                'label' => 'O museu é itinerante?',
                'type' => 'select',
                'options' => ['sim', 'não']
            ],
            
            'itinerante_dependeRecursos' => [
                'label' => 'O museu depende de recursos financeiros de outra instituição para a itinerância da exposição?',
                'type' => 'select',
                'options' => [
                    '' => 'não se aplica',
                    'sim', 
                    'não'
                ]
            ],
            
            // EXPOSIÇÔES
            'exposicoes_duracao' => [
                'label' => 'Duração das exposições',
                'type' => 'select',
                'options' => [
                    'Possui SOMENTE exposição de longa duração (mais de um ano em exposição)',
                    'Possui exposição de longa duração e realiza exposições de curta duração (até um ano em exposição)',
                    'Realiza SOMENTE exposições de curta duração',
                    'NÃO realiza exposições'
                ]
            ],
            
            'horario_segunda_das' => [
                'label' => 'Aberto nas segundas-feiras das (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_segunda_ate' => [
                'label' => 'Aberto nas segundas-feiras até as (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_terca_das' => [
                'label' => 'Aberto nas terças-feiras das (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_terca_ate' => [
                'label' => 'Aberto nas terças-feiras até as (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_quarta_das' => [
                'label' => 'Aberto nas quartas-feiras das (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_quarta_ate' => [
                'label' => 'Aberto nas quartas-feiras até as (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_quinta_das' => [
                'label' => 'Aberto nas quintas-feiras das (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_quinta_ate' => [
                'label' => 'Aberto nas quintas-feiras até as (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_sexta_das' => [
                'label' => 'Aberto nas sextas-feiras das (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_sexta_ate' => [
                'label' => 'Aberto nas sextas-feiras até as (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_sabado_das' => [
                'label' => 'Aberto nos sábados das (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_sabado_ate' => [
                'label' => 'Aberto nos sábados até as (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_domingo_das' => [
                'label' => 'Aberto nos domingos das (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            'horario_domingo_ate' => [
                'label' => 'Aberto nos domingos até as (00:00)',
                'validations' => [
                    'v::date("H:i:s")'
                ]
            ],
            // tipologia
            'tipo' => [
                'label' => 'Tipo',
                'type' => 'select',
                'options' => [
                    'Tradicional/Clássico',
                    'Virtual',
                    'Museu de território/Ecomuseu',
                    'Unidade de conservação da natureza',
                    'Jardim zoológico, jardim botânico herbário, oceanário ou planetário'
                ]
            ],
            
            'tipo_tematica' => [
                'label' => 'Temática do museu',
                'type' => 'select',
                'options' => [
                    'Artes, arquitetura e linguística',
                    'Antropologia e arqueologia',
                    'Ciências exatas, da terra, biológicas e da saúde',
                    'História',
                    'Educação, esporte e lazer',
                    'Meios de comunicação e transporte',
                    'Produção de bens e serviços',
                    'Defesa e segurança pública',
                ]
            ],
            
            'tipo_unidadeConservacao' => [
                'label' => 'Tipo/categoria de manejo da Unidade de Conservação',
                'type' => 'select',
                'options' => [
                    '' => 'Não se aplica',
                    'Proteção integral',
                    'Uso sustentável'
                ]
            ],
            
            'tipo_unidadeConservacao_protecaoIntegral' => [
                'label' => 'Tipo de unidade de conservação integral',
                'type' => 'select',
                'options' => [
                    '' => 'Não se aplica',
                    'Estação Ecológica',
                    'Monumento Natural',
                    'Parque',
                    'Refúgio da Vida Silvestre',
                    'Reserva Biológica'
                ]
            ],
            
            'tipo_unidadeConservacao_usoSustentavel' => [
                'label' => 'Tipo de unidade de uso sustentável',
                'type' => 'select',
                'options' => [
                    '' => 'Não se aplica',
                    'Floresta',
                    'Reserva Extrativista',
                    'Reserva de Desenvolvimento Sustentável',
                    'Reserva de Fauna',
                    'Área de Proteção Ambiental',
                    'Área de Relevante Interesse Ecológico',
                    'RPPN (Reserva Particular do Patrimônio Natural)'
                ]
            ],
            
            
            'instalacoes' => [
                'label' => 'Instalações básicas e serviços oferecidos',
                'multiselect',
                'options' => [
                    'Bebedouro',
                    'Estacionamento',
                    'Guarda-volumes',
                    'Livraria',
                    'Loja',
                    'Restaurante e/ou Lanchonete',
                    'Sanitário',
                    'Teatro/Auditório'
                ]
            ],
            'instalacoes_capacidadeAuditorio' => [
                'label' => 'Capacidade do teatro/auditório (assentos)',
                'type' => 'int',
                'validations' => [
                    'v::numeric()' => 'a capacidade do teatro/auditório deve ser um número inteiro'
                ]
            ],
            'servicos_visitaGuiada' => [
                'label' => 'O museu promove visitas guiadas?',
                'type' => 'select',
                'options' => [ 'sim', 'não']
            ],
            'servicos_atendimentoEstrangeiros' => [
                'label' => 'Atendimento em outros idiomas',
                'multiselect',
                'allowOther' => true,
                'allowOtherText' => 'Outros',
                'options' => [
                    'Sinalização visual',
                    'Material de divulgação impresso',
                    'Audioguia',
                    'Guia, monitor e/ou mediador'
                ]
            ],
            'acessibilidade_visual' => [
                'label' => 'O museu oferece instalações e serviços destinados às pessoas com deficiências auditivas e visuais?',
                'multiselect',
                'allowOther' => true,
                'allowOtherText' => 'Outros',
                'options' => [
                    'Guia multimídia (com monitor)',
                    'Maquetes táteis ou mapas em relevo',
                    'Obras e reproduções táteis',
                    'Tradutor de Linguagem Brasileira de Sinais (LIBRAS)',
                    'Texto/Etiquetas em braile com informações sobre os objetos expostos'
                ]
            ],
            'arquivo_possui' => [
                'label' => 'O museu possui arquivo histórico?',
                'type' => 'select',
                'options' => [ 'sim', 'não']
            ],
            'arquivo_acessoPublico' => [
                'label' => 'O arquivo tem acesso ao público?',
                'type' => 'select',
                'options' => [ 'sim', 'não']
            ],
            'biblioteca_possui' => [
                'label' => 'O Museu possui biblioteca?',
                'type' => 'select',
                'options' => [ 'sim', 'não']
            ],
            'biblioteca_acessoPublico' => [
                'label' => 'A biblioteca tem acesso ao público?',
                'type' => 'select',
                'options' => [ 
                    '' => 'não se aplica',
                    'sim' => 'sim', 
                    'não' => 'não',
                ]
            ],
            
            // acervo
            'acervo_comercializacao' => [
                'label' => 'Comercialização do acervo',
                'type' => 'select',
                'options' => [ 
                    "APENAS comercializável",
                    "APENAS Não comercializável",
                    "Comercializável e não comercializável"
                ]
            ],
            
            'acervo_propriedade' => [
                'label' => 'Propriedade do acervo',
                'type' => 'select',
                'options' => [ 
                    'Possui SOMENTE acervo próprio',
                    'Possui acervo próprio e em comodato',
                    'Acervo compartilhado entre órgãos/setores da mesma entidade mantenedora',
                    'Possui SOMENTE acervo em comodato/empréstimo',
                    'NÃO possui acervo',
                ]
            ],
            
            'acervo_comodato_formalizado' => [
                'label' => 'O comodato/empréstimo está formalizado por meio de documento legal?',
                'type' => 'select',
                'options' => [
                    '' => 'não se aplica',
                    'sim' => 'sim', 
                    'não' => 'não'
                ]
            ],
            
            'acervo_comodato_duracao' => [
                'label' => 'Duração do comodato/empréstimo (em meses)',
                'type' => 'numeric',
                'validations' => [
                    'v::numeric()' => ''
                ]
            ],
            
            'acervo_material' => [
                'label' => 'O museu possui também acervo material?',
                'type' => 'select',
                'options' => [
                    'sim' => 'sim', 
                    'não' => 'não'
                ]
            ],
            
            'acervo_material_emExposicao' => [
                'label' => 'O acervo material encontra-se em exposição?',
                'type' => 'select',
                'options' => [
                    '' => 'não se aplica',
                    'sim' => 'sim', 
                    'não' => 'não'
                ]
            ],
            
            'acervo_nucleoEdificado' => [
                'label' => 'Núcleo Edificado',
                'type' => 'multiselect',
                'options' => [
                    'O museu NÃO possui acervo em exposições em núcleo edificado',
                    'A exposição do museu está no próprio território',
                    'O museu possui núcleo(s) edificado(s) com acervo em exposição',
                    'O Museu possui núcleo edificado apenas como sede técnico-administrativa',
                    'O Museu NÃO possui núcleo edificado e NÃO possui sede técnico-administrativa',
                    'O acervo do museu é composto de núcleos edificados'
                ]
            ],
            
            'atividadePrincipal' => [
                'label' => 'Em relação à sua atividade principal, indique a opção que melhor caracterize a instituição',
                'type' => 'singleselect',
                'options' => [
                    'Arquivo',
                    'Biblioteca',
                    'Centro cultural',
                    'Museu',
                    'Galeria'
                ]
            ],
            
            'caraterComunitario' => [
                'label' => 'O museu é de carater comunitário?',
                'type' => 'select',
                'options' => [ 'sim', 'não']
            ],
            
            'comunidadeRealizaAtividades' => [
                'label' => 'A comunidade realiza atividades museológicas?',
                'type' => 'select',
                'options' => [ 'sim', 'não']
            ],
            
            'ingresso_cobrado' => [
                'label' => 'O ingresso ao museu é cobrado?',
                'type' => 'select',
                'options' => [ 'sim', 'não', 'contribuição voluntária']
            ],
            
            'ingresso_valor' => [
                'label' => 'Descrição do valor do ingresso ao museu',
                'type' => 'text'
            ],
            
            // GESTÂO
            'gestao_regimentoInterno' => [
                'label' => 'O museu posui regimento interno?',
                'type' => 'select',
                'options' => ['sim', 'não']
            ],
            
            'gestao_planoMuseologico' => [
                'label' => 'O museu possui plano museológico?',
                'type' => 'select',
                'options' => ['sim', 'não']
            ],
            
            'gestao_politicaAquisicao' => [
                'label' => 'O museu possui política de aquisição de acervo?',
                'type' => 'select',
                'options' => ['sim', 'não']
            ],
            
            'gestao_politicaDescarte' => [
                'label' => 'O museu possui política de descarte de acervo?',
                'type' => 'select',
                'options' => ['sim', 'não']
            ],
            
            
            
            // FALTA DEFINIR SE VAI PARA O CORE
            'EnCorrespondencia_mesmo' => [
                'label' => 'O endereço de correspondência é o mesmo de visitação?',
                'type' => 'select',
                'options' => [ 'sim', 'não' ]
            ],
            
            'endereco_correspondencia' => [
                'label' => 'Endereço de correspondência'
            ],
            
            'EnCorrespondencia_CEP' => [
                'label' => 'CEP',
            ],
            'EnCorrespondencia_Nome_Logradouro' => [
                'label' => 'Logradouro',
            ],
            'EnCorrespondencia_Num' => [
                'label' => 'Número',
            ],
            'EnCorrespondencia_Complemento' => [
                'label' => 'Complemento',
            ],
            'EnCorrespondencia_CaixaPostal' => [
                'label' => 'Caixa Postal',
            ],
            'EnCorrespondencia_Bairro' => [
                'label' => 'Bairro',
            ],
            'EnCorrespondencia_Municipio' => [
                'label' => 'Município',
            ],
            'EnCorrespondencia_Estado' => [
                'label' => 'Estado',
                'type' => 'select',
                'options' => array(
                    'AC'=>'Acre',
                    'AL'=>'Alagoas',
                    'AP'=>'Amapá',
                    'AM'=>'Amazonas',
                    'BA'=>'Bahia',
                    'CE'=>'Ceará',
                    'DF'=>'Distrito Federal',
                    'ES'=>'Espírito Santo',
                    'GO'=>'Goiás',
                    'MA'=>'Maranhão',
                    'MT'=>'Mato Grosso',
                    'MS'=>'Mato Grosso do Sul',
                    'MG'=>'Minas Gerais',
                    'PA'=>'Pará',
                    'PB'=>'Paraíba',
                    'PR'=>'Paraná',
                    'PE'=>'Pernambuco',
                    'PI'=>'Piauí',
                    'RJ'=>'Rio de Janeiro',
                    'RN'=>'Rio Grande do Norte',
                    'RS'=>'Rio Grande do Sul',
                    'RO'=>'Rondônia',
                    'RR'=>'Roraima',
                    'SC'=>'Santa Catarina',
                    'SP'=>'São Paulo',
                    'SE'=>'Sergipe',
                    'TO'=>'Tocantins',
                )
            ],
        ];
    }

    function register() {
        parent::register();
        $app = App::i();
        $app->hook('app.register', function(&$registry) {
            $group = null;
            $registry['entity_type_groups']['MapasCulturais\Entities\Space'] = array_filter($registry['entity_type_groups']['MapasCulturais\Entities\Space'], function($item) use (&$group) {
                if ($item->name === 'Museus') {
                    $group = $item;
                    return $item;
                } else {
                    return null;
                }
            });

            $registry['entity_types']['MapasCulturais\Entities\Space'] = array_filter($registry['entity_types']['MapasCulturais\Entities\Space'], function($item) use ($group) {
                if ($item->id >= $group->min_id && $item->id <= $group->max_id) {
                    return $item;
                } else {
                    return null;
                }
            });
        });
        
        $terms = [
            'Antropologia e Etnografia',
            'Arqueologia',
            'Arquivístico',
            'Artes Visuais',
            'Ciências Naturais e História Natural',
            'Ciência e Tecnologia',
            'História',
            'Imagem e Som',
            'Virtual',
            'Outros'
        ];

        $taxo_def = new \MapasCulturais\Definitions\Taxonomy(101, 'mus_area', 'Tipologia de Acervo', $terms, false, true);

        $app->registerTaxonomy('MapasCulturais\Entities\Space', $taxo_def);
    }


    /**
    * Returns a verified entity
    * @param type $entity_class
    * @return \MapasCulturais\Entity
    */
    function getOneVerifiedEntity($entity_class) {
        $app = \MapasCulturais\App::i();

        $cache_id = __METHOD__ . ':' . $entity_class;

        if($app->cache->contains($cache_id)){
            return $app->cache->fetch($cache_id);
        }



        $controller = $app->getControllerByEntity($entity_class);

        if ($entity_class === 'MapasCulturais\Entities\Event') {
            $entities = $controller->apiQueryByLocation(array(
                '@from' => date('Y-m-d'),
                '@to' => date('Y-m-d', time() + 28 * 24 * 3600),
                'isVerified' => 'EQ(true)',
                '@select' => 'id'
            ));

        }elseif ($entity_class === 'MapasCulturais\Entities\Space') {
            $entities = $controller->apiQuery([
                '@select' => 'id',
                'mus_verificado' => 'EQ(1)'
            ]);
        }else{

            $entities = $controller->apiQuery([
                '@select' => 'id',
                'isVerified' => 'EQ(true)'
            ]);
        }

        $ids = array_map(function($item) {
            return $item['id'];
        }, $entities);

        if ($ids) {
            $id = $ids[array_rand($ids)];
            $result = $app->repo($entity_class)->find($id);
            $result->refresh();
        } else {
            $result = null;
        }

        $app->cache->save($cache_id, $result, 120);

        return $result;
    }
}
