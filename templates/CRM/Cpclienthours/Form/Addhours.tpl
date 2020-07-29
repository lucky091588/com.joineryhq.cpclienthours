<div class="crm-block crm-form-block">
  {if $rows}
    <table class="form-layout">

    <tr class="crm-addhours-form-block-team_name">
      <td class="label">{ts}Team Name{/ts}</td>
      <td class="view-value">
        {$team.display_name}
      </td>
    </tr>
    <tr class="crm-addhours-form-block-team_nickname">
      <td class="label">{ts}Team Nickname{/ts}</td>
      <td class="view-value">
        {$team.nick_name}
      </td>
    </tr>
    <tr class="crm-addhours-form-block-service_date">
      <td class="label">{$form.service_date.label}</td>
      <td class="view-value">
        {$form.service_date.html}
      </td>
    </tr>
    </table>

    <table cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr class="columnheader">
          <th>{ts}Name{/ts}</th>
          <th>{ts}Hours{/ts}</th>
          <th>{ts}Help type{/ts}</th>
        </tr>
      </thead>
      <tbody>
      {foreach from=$rows item=row}
        {assign var=hoursElementName value=$row.hoursElementName}
        {assign var=helpTypeElementName value=$row.helpTypeElementName}
        <tr id="cpclienthours-addhours-row-{$row.clientCid}" class="cpclienthours-addhours-row {cycle values="odd-row,even-row"}">
          <td>{$row.sortName}</td>
          <td>{$form.$hoursElementName.html}</td>
          <td>{$form.$helpTypeElementName.html}</td>
        </tr>
      {/foreach}
      </tbody>
    </table>
  {else}
    {ts}This team has no active volunteer relationships.{/ts}
  {/if}

  {* FIELD EXAMPLE: OPTION 2 (MANUAL LAYOUT)

    <div>
      <span>{$form.favorite_color.label}</span>
      <span>{$form.favorite_color.html}</span>
    </div>

  {* FOOTER *}
  <div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
</div>

