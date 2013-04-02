<table class="custombox-theorema" cellspacing="0" width="100%">
<tr valign="middle">
    <td class="custombox-header-thumb theorema" width="2%" rowspan="<%%rowspan%%>">
    </td>
    <td class="custombox-header-caption theorema" width="98%">
        Théorème
    </td>
</tr>
<tr valign="top">
    <td class="custombox-content theorema">
        <%%theorematext%%>
    </td>
</tr>
<tr valign="top">
    <td class="custombox-corollaries theorema">
        <%%corollarylist%%>
    </td>
</tr>
<%if %%showdemonstration%% %>
<tr valign="top">
    <td class="custombox-demonstration theorema">
    	<div class="custombox-demonstration-caption theorema">Démonstration</div>
        <div class="custombox-demonstration theorema"><%%demonstration%%></div>
    </td>
</tr>
<%endif %>
</table>