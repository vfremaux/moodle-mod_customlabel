<table class="custombox-courseclassifier">
    <tr valign="top">
        <th class="custombox-title courseclassifier" colspan="2">
            <%%tablecaption%%>
        </th>
    </tr>
    <tr valign="top">
        <td class="custombox-param courseclassifier" width="30%">
            Classifier Level 1:
        </td>
        <td class="custombox-value courseclassifier">
            <%%level0%%>
        </td>
    </tr>
    <%if %%uselevels >= 2%% %>
    <tr valign="top">
        <td class="custombox-param courseclassifier" width="30%">
            Classifier level 2:
        </td>
        <td class="custombox-value courseclassifier">
            <%%level1%%>
        </td>
    </tr>
    <%endif %>
    <%if %%uselevels >= 3%% %>
    <tr valign="top">
        <td class="custombox-param courseclassifier" width="30%">
            Classifier level3:
        </td>
        <td class="custombox-value courseclassifier">
            <%%level2%%>
        </td>
    </tr>
    <%endif %>
</table>
<%if %%showpeople%% %>
<table class="custombox-courseclassifier other">
    <tr valign="top">
        <th class="custombox-title courseclassifier" colspan="2">
            Other classifying information
        </th>
    </tr>
    <tr valign="top">
        <td class="custombox-param courseclassifier" width="30%">
            Public:
        </td>
        <td class="custombox-value courseclassifier">
            <%%people%%>
        </td>
    </tr>
</table>
<%endif %>