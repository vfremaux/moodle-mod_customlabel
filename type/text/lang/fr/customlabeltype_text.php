<?php

$string['text:view'] = 'Peut voir le contenu';
$string['text:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Texte simple';
$string['typename'] = 'Texte simple';
$string['configtypename'] = 'Active le type Texte simple';
$string['textcontent'] = 'Contenu';
$string['readmorecontent'] = 'Texte supplémentaire';
$string['initiallyvisible'] = 'Visible au chargement';
$string['readmore'] = 'Lire plus...';
$string['readless'] = 'Lire moins...';

$string['family'] = 'generic';

$string['template'] = '
<!-- standard default template for unclassed label. Don\'t change -->
<div class="custombox-text">
<%%textcontent%%>
</div>
<%if %%readmorecontent%% %>
<div class="custombox-text readmorelink">
    <a href="javascript:togglecustomstring(\'<%%customid%%>\', \'Lire plus...\', \'Lire moins...\')" ><span id="customctl<%%customid%%>"><%%initialstring%%></span></a>
</div>
<div class="custombox-text readmore" id="custom<%%customid%%>">
<%%readmorecontent%%>
</div>
<script type="text/javascript">
setupcustomstring(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'Lire plus...\', \'Lire moins...\');
</script>
<%endif %>';