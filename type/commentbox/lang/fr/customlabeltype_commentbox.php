<?php

$string['commentbox:view'] = 'Peut voir le contenu';
$string['commentbox:addinstance'] = 'Peut ajouter une instance';

$string['pluginname'] = 'Elément de cours : Boite de commentaire';
$string['typename'] = 'Boîtes de commentaires';
$string['configtypename'] = 'Active le type Boites de commentaires';
$string['comment'] = 'Commentaire ';
$string['readmorecontent'] = 'Lire plus';
$string['initiallyvisible'] = 'Visible au chargement';

$string['family'] = 'generic';

$string['template'] = '<div class="custombox-commentbox">
<%%comment%%>
</div>
<%if %%readmorecontent%% %>
<div class="custombox-commentbox readmorelink">
    <a href="javascript:togglecustomstring(\'<%%customid%%>\', \'Lire plus...\', \'Lire moins...\')"><span id="customctl<%%customid%%>"><%%initialstring%%></span></a>
</div>
<div class="custombox-commentbox readmore" id"custom<%%customid%%>">
<%%readmorecontent%%>
</div>
<%endif %>
<script type="text/javascript">
setupcustomstring(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'Lire plus...\', \'Lire moins...\');
</script>
';