<table class="custombox-authordata">
	<%if %%tablecaption%% %>
    <tr valign="top">
        <th class="custombox-title authordata" colspan="2">
            <%%tablecaption%%>
        </th>
    </tr>
    <%endif %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Author<%if %%author2%% %>s<%endif %> : 
        </td>
        <td class="custombox-value authordata">
            <%if %%thumb3%% %>
            <img src="<%%thumb3%%>" title="<%%author3%%>" style="float:right" width="80"  height="120"/>
            <%endif %>
            <%if %%thumb2%% %>
            <img src="<%%thumb2%%>" title="<%%author2%%>" style="float:right;margin-right:10px"  width="80"  height="120" />
            <%endif %>
            <img src="<%%thumb1%%>" title="<%%author1%%>" style="float:right;margin-right:10px"  width="80"  height="120" />
            <%%author1%%> 
            <%%author2%%>
            <%%author3%%>
        </td>
    </tr>
	<%if %%showinstitution%% %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Institution:
        </td>
        <td class="custombox-value authordata">
            <%%institution%%>
        </td>
    </tr>
	<%endif %>
	<%if %%showdepartment%% %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Department:
        </td>
        <td class="custombox-value authordata">
            <%%department%%>
        </td>
    </tr>
	<%endif %>
	<%if %%showcontributors%% %>
    <tr valign="top">
        <td class="custombox-param authordata">
            Contributors:
        </td>
        <td class="custombox-value authordata">
            <%%contributors%%>
        </td>
    </tr>
	<%endif %>
</table>