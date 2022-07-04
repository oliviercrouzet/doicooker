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
            $buttons = '<li style="padding-left:40%"><a href='.$url.'>Afficher</a>&nbsp;|&nbsp;<a href="'.$url.'&amp;download=1">Télécharger</a></li>';
            View::$page = preg_replace('/(<h4>Fonctions<\/h4>.*?)(<\/ul>\s*<\/div>)/s','$1<li>Produire un fichier de dépôt DOI (xml) :</li>'.$buttons.'$2',View::$page);
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
