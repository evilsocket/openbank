<div class="panel panel-default">

  <div class="panel-heading">
    Public Keys
    <span style="float:right">
      <a href="#" id="add_key" data-toggle="tooltip" data-placement="top" title="Add a new public key." class="btn btn-xs btn-success"><i class="fa fa-plus"></i></a>
    </span>
  </div>

  <table class="table table-hover" style="font-size:12px;">
    <thead>
      <tr>
        <td width="10%"></td>
        <td></td>
        <td width="15%"></td>
        <td width="5%"></td>
      <tr>
    </thead>
    <tbody id="keys">
      <tr ng-repeat="key in keys">
        <td ng-bind="key.updated_at"></td>
        <td><b ng-bind="key.label"></b></td>
        <td ng-bind="key.balance + ' ฿'"></td>
        <td>
          <a href="#" class="btn btn-xs btn-danger key_delete" data-key="<% key.value %>">
            <i class="fa fa-trash"></i>
          </a>
          &nbsp;
          <a href="#" class="btn btn-xs btn-warning key_edit" data-label="<% key.label %>" data-key="<% key.value %>">
            <i class="fa fa-pencil-square-o"></i>
          </a>
        </td>
      </tr>
    </tbody>
  </table>

</div>
