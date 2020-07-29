CRM.$(function($) {


  var handleSelectChange = function handleSelectChange(e){
    $('input#value').val(this.value);
    $('input#label').val(this.value);
  }

  jqEl = $('input#value');
  jqEl
    .hide()
    .before('\
      <select class="crm-form-select crm-select2" id="cpclienthours-select-value">\n\
        <option></option>\n\
      </select>\n\
    ');

  //Once we have the option values... we can continue with processing fields with values
  $.each(CRM.vars.cpclienthours.serviceTypeOptions, function(opValue, opLabel) {
    $('select#cpclienthours-select-value')
      .append($("<option></option>")
      .attr("value", opValue)
      .text(opLabel));

    if (opValue == jqEl.val()) {
      $('select#cpclienthours-select-value').val(opValue);
    }
  });
  $('select#cpclienthours-select-value').change(handleSelectChange);

  // hide description.
  $('tr.crm-admin-options-form-block-label span.description').hide();
  $('tr.crm-admin-options-form-block-value span.description').hide();

  // Place label(hours) after value(role)
  $('tr.crm-admin-options-form-block-value').after($('tr.crm-admin-options-form-block-label'));
});
