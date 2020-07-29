CRM.$(function($) {
  var idx;
  var colname;

  // hide columns.
  var colnames = [
    'label',
    'order',
    'is_reserved',
    'is_active',
  ];
  for (i in colnames) {
    colname = colnames[i];
    idx = CRM.$('table#options td.crm-admin-options-'+ colname).index() + 1; 
    CRM.$('table#options td.crm-admin-options-'+ colname +', table#options thead th:nth-child('+ idx +')').hide();
  }

  // Hide disable links.
  $('a.crm-enable-disable').hide();
  
  // rename columns:
  idx = CRM.$('table#options td.crm-admin-options-description').index() + 1; 
  CRM.$('table#options thead th:nth-child('+ idx +')').html('Hours');

  idx = CRM.$('table#options td.crm-admin-options-value').index() + 1; 
  CRM.$('table#options thead th:nth-child('+ idx +')').html('"Service Type" value');
  
  // Make "description" editor just a text field (not textarea)
  CRM.$('table#options td.crm-admin-options-description').attr('data-type', 'text');

});
