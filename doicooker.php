<?php
// ma classe doit absolument étendre la classe de base 'Plugins'
class DoiCooker extends Plugins
{
    // pas besoin d'initialiser quoique ce soit à l'activation/désactivation du plugin
    // il faut toutefois les déclarer pour respecter la cohérence avec la classe parente
    public function enableAction(&$context, &$error) {
        if(!parent::_checkRights(LEVEL_ADMINLODEL)) { return; }
    }
    public function disableAction(&$context, &$error) {
        if(!parent::_checkRights(LEVEL_ADMINLODEL)) { return; }
    }

    public function postview(&$context)
    {
        $requestedlevel = $this->_config['userrights']['value'] ?? LEVEL_ADMINLODEL;
        if (!parent::_checkRights($requestedlevel)) { return; }

        if ($context['view']['tpl'] == 'doi' && isset($context['download'])) {
            $domxml = new DOMDocument('1.0','UTF-8');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            $domxml->loadXML(View::$page);
            $xml = $domxml->saveXML();
            $prefix = strstr($context['doi']['prefix'],'/',true);
            $filename = $prefix.'-'.$context['site'].'-'.$context['id'].'.xml';

            header('Content-Disposition: attachment; filename='.$filename);
            echo $xml;
            exit;
        }

        if ($context['view']['tpl'] == 'edit_entities_edition') {
            $id = $context['id'];
            $type =$context['type']['type'];

            // on n'affiche pas le lien pour un type de document qu'on ne souhaite pas moissonner
            $harvestedtypes = explode(',',$this->_config['harvestedtypes']['value']);
            if (!in_array($type,array_merge($harvestedtypes, ['numero']))) { return; } // le type numero est moissonnable par défaut.

            $url = './?do=_doicooker_cook&amp;type='.$type.'&amp;id='.$id;
            $script = "<script>
                const xmllinks = document.querySelectorAll('.doi');
                xmllinks.forEach(function(link) {
                    link.addEventListener('click',function() {
                        if (document.querySelector('#datedoi').checked) this.setAttribute('href',this.getAttribute('href') + '&checkdatepubli=1');
                        else this.setAttribute('href',this.getAttribute('href').replace('&checkdatepubli=1',''));
                    },false);
                });
            </script>";
            $htmlfunc = '<li>Produire un fichier de dépôt DOI (xml) :</li>';
            $htmlfunc .= '<li style="padding-left:4%;margin-top:-3px;color:grey">';
            $htmlfunc .= '<label>date de publ. = date de la publ. électronique</label><input id="datedoi" type="checkbox"></li>';
            $htmlfunc .= '<li style="padding-left:4%;margin-top:-3px">';
            $htmlfunc .= '<a class="doi" href='.$url.'>Afficher</a>&nbsp;|&nbsp;<a class="doi" href="'.$url.'&amp;download=1">Télécharger</a></li>';

            View::$page = preg_replace('/(<div class="advancedFunc">.*?<h4>Fonctions<\/h4>.*?)(<\/ul>\s*<\/div>)/s','$1'.$htmlfunc.'$2'.$script,View::$page);
        }
    }

    public function cookAction(&$context,&$errors) 
    {
        // données site
        C::set('view.base_rep.doi', 'doicooker');  
        C::set('doi.prefix', $this->_config['prefix']['value']);
        C::set('doi.depositor', $this->_config['depositor']['value']);
        C::set('doi.email', $this->_config['email']['value']);
        C::set('doi.license', $this->_config['license']['value']);

        $harvested = preg_replace('/([a-z]+)/',"'$1'",$this->_config['harvestedtypes']['value']);
        C::set('doi.harvestedtypes', $harvested);

        header('Cache-Control: no-cache');
        header('Content-Type: application/xml');
        View::getView()->render('doi');

        return '_ajax';
    }

}
