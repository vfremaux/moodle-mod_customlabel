<!-- This is a layout template for the custom type NEWTYPE -->
<!-- There should be a template for all used languages -->
<!-- Remind : Template must be UTF8 encoded -->

<!-- The first line calls the customization CSS -->
<div class="labelcontent">
<table class="customlabeldemo">
    <tr>
        <th colspan="2">
            <%%title%%>
        </th>
    <tr>
    <tr>
        <td class="param">
            Text example
        </td>
        <td class="value">
            <%%smalltext%%>
        </td>
    </tr>
    <tr>
        <td class="param">
            Long text example
        </td>
        <td class="value">
            <%%parag%%>
        </td>
    </tr>
    <tr>
        <td class="param">
            One choice list
        </td>
        <td class="value">
            <%%list%%>
        </td>
    </tr>
    <tr>
        <td class="param">
            Mutiple choice list
        </td>
        <td class="value">
            <%%listmultiple%%>
        </td>
    </tr>
    <tr class="locked">
        <td class="param">
            Sample of field locked in editing for authorized roles
        </td>
        <td>
            <%%lockedfield%%>
        </td>
    </tr>
</table>
</div>