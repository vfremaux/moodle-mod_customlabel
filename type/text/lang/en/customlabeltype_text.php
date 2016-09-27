<?php

$string['text:view'] = 'Can view the content';
$string['text:addinstance'] = 'Can add an indstance';

$string['pluginname'] = 'Course element : Simple Text';
$string['typename'] = 'Simple Text';
$string['configtypename'] = 'Enable subtype Simple Text';
$string['textcontent'] = 'Content';
$string['readmorecontent'] = 'Read more content';
$string['initiallyvisible'] = 'Initally visible';
$string['readmore'] = 'Read more...';
$string['readless'] = 'Read less...';

$string['family'] = 'generic';

$string['template'] = '
<!-- standard default template for unclassed label. Don\'t change -->
<div class="custombox-text">
<%%textcontent%%>
</div>
<%if %%readmorecontent%% %>
<div class="custombox-text readmorelink">
    <a href="javascript:togglecustomstring(\'<%%customid%%>\', \'Read more...\', \'Read less...\')" ><span id="customctl<%%customid%%>"><%%initialstring%%></span></a>
</div>
<div class="custombox-text readmore" id="custom<%%customid%%>">
<%%readmorecontent%%>
</div>
<%endif %>
<script type="text/javascript">
setupcustomstring(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'Read more...\', \'Read less...\');
</script>
';