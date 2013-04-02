<table class="custombox-coursedata">
	<%if %%tablecaption%% %>
    <tr valign="top">
        <th class="custombox-title coursedata" colspan="2">
            <%%tablecaption%%>
        </th>
    </tr>
    <%endif %>
	<%if %%showidnumber%% %>
    <tr valign="top">
        <td class="custombox-param coursedata">
            Training ID:
        </td>
        <td class="custombox-value coursedata">
            <%%idnumber%%>
        </td>
    </tr>
	<%endif %>
	<%if %%showgoals%% %>
    <tr valign="top">
        <td class="custombox-param coursedata">
            Goals:
        </td>
        <td class="custombox-value coursedata">
            <%%goals%%>
        </td>
    </tr>
	<%endif %>
	<%if %%showobjectives%% %>
    <tr valign="top">
        <td class="custombox-param coursedata">
            Acquisitions:
        </td>
        <td class="custombox-value coursedata">
            <%%objectives%%>
        </td>
    </tr>
	<%endif %>
	<%if %%showconcepts%% %>
    <tr valign="top">
        <td class="custombox-param coursedata">
            Concepts:
        </td>
        <td class="custombox-value coursedata">
            <%%concepts%%>
        </td>
    </tr>
	<%endif %>
	<%if %%showduration%% %>
    <tr valign="top">
        <td class="custombox-param coursedata">
            Duration:
        </td>
        <td class="custombox-value coursedata">
            <%%duration%%>
        </td>
    </tr>
	<%endif %>
	<%if %%showteachingorganization%% %>
    <tr valign="top">
        <td class="custombox-param coursedata">
            Teaching organization:
        </td>
        <td class="custombox-value coursedata">
            <%%teachingorganization%%>
        </td>
    </tr>
	<%endif %>
	<%if %%showprerequisites%% %>
    <tr valign="top">
        <td class="custombox-param coursedata">
            Prerequisites:
        </td>
        <td class="custombox-value coursedata">
            <%%prerequisites%%>
        </td>
    </tr>
	<%endif %>
	<%if %%showfollowers%% %>
    <tr valign="top">
        <td class="custombox-param coursedata">
            Following courses:
        </td>
        <td class="custombox-value coursedata">
            <%%followers%%>
        </td>
    </tr>
	<%endif %>
</table>