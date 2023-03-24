<?php
// ma classe doit absolument étendre la classe de base 'Plugins'
class DoiCooker extends Plugins
{
    // pas besoin d'initialiser quoique ce soit à l'activation/désactivation du plugin
    // il faut toutefois les déclarer pour respecter la cohérence avec la classe parente
    public function enableAction(&$context, &$error) {}
    public function disableAction(&$context, &$error) {}

    public function postview(&$context)
    {
        $pluginrights = isset($this->_config['userrights']['value']) ? $this->_config['userrights']['value']:128;
        if (isset($context['view']['base_rep']['doi']) &&  $context['lodeluser']['rights'] >= $pluginrights) {
            // workaround pour ajouter la balise body qui autrement fait planter le parsing
            View::$page = preg_replace('/(<journal>.*?<\/journal>)/s',"<body>$1</body>",View::$page);

        }
        if ($context['view']['tpl'] == 'edit_entities_edition' && $context['lodeluser']['rights'] >= $pluginrights) {
            $id = $context['id'];
            $type =$context['type']['type'];
            
            // on n'affiche pas le lien pour un type de document qu'on ne souhaite pas moissonner
            $harvested = $this->_config['harvestedtypes']['value']; 
            if (! preg_match("/$type/",$harvested.'numero')) return;

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

        $harvested = preg_replace('/([a-z]+)/',"'$1'",$this->_config['harvestedtypes']['value']);
        C::set('doi.harvestedtypes', $harvested);

        View::getView()->render('doi');
        return _ajax;
        
    }

}
