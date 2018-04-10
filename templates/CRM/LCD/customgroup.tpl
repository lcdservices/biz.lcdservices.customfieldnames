{* FILE: customfieldnames/templates/customgroupfield.tpl to add custom field for custom data set*}
<table id="lcd-custom-fields" class="form-layout">
  <tbody>
    <tr id="table_name-tr">
      <td class="label">{$form.table_name.label}</td>
      <td class="html-adjust">{$form.table_name.html}</td>
    </tr>

    <tr id="name-tr">
      <td class="label">{$form.name.label}</td>
      <td class="html-adjust">{$form.name.html}</td>
    </tr>
  </tbody>
</table>

<script type="text/javascript">
  cj('#lcd-custom-fields').insertAfter('div.crm-submit-buttons:first');
</script>