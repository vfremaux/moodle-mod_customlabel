<?php

$string['commentbox:view'] = 'Can view the content';
$string['commentbox:addinstance'] = 'Can add an instance';

$string['pluginname'] = 'Course element : Comment';
$string['typename'] = 'Comment boxes';
$string['configtypename'] = 'Enable subtype Comment boxes';
$string['comment'] = 'Comment ';
$string['readmorecontent'] = 'Read more content';
$string['initiallyvisible'] = 'Initially visible';

$string['family'] = 'generic';

$string['template'] = '<div class="custombox-commentbox">
<%%comment%%>
</div>
<%if %%readmorecontent%% %>
<div class="custombox-commentbox readmorelink">
    <a href="javascript:togglecustomstring(\'<%%customid%%>\', \'Read more...\', \'Read less...\')"><span id="customctl<%%customid%%>"><%%initialstring%%></span></a>
</div>
<div class="custombox-commentbox readmore" id"custom<%%customid%%>">
<%%readmorecontent%%>
</div>
<%endif %>
<script type="text/javascript">
setupcustomstring(\'<%%customid%%>\', \'<%%initiallyvisible%%>\', \'Read more...\', \'Read less...\');
</script>
';
