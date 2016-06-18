<div class="row">
  <div ng-repeat="key in keys" class="col-md-4">
  <div class="panel panel-default key_edit" style="cursor: pointer;" data-label="<% key.label %>" data-key="<% key.value %>">
      <div class="panel-heading">
        <span ng-bind="key.label"></span>
        <span style="float:right">
          <a href="#" class="btn btn-xs btn-danger key_delete" data-key="<% key.value %>">
            <i class="fa fa-trash"></i>
          </a>
        </span>
      </div>
      <div class="panel-body" style="font-size:30px; text-align:center;">
        <center>
          <span ng-bind="key.balance + ' ฿'">Loading ...</span>
        </center>
      </div>
      <table class="table">
        <tr>
          <td>
            <center>
              <small style="color:#ccc">Last update <span ng-bind="key.updated_at"></span></small>
            </center>
          </td>
        </tr>
      </table>
  </div>
  </div>

  <div class="col-md-4">
  <div id="add_key" class="panel panel-default" style="padding: 33px; cursor: pointer; background-color:#5cb85c; border-color: #4cae4c; color:white">
      <div class="panel-body" style="font-size:26px; text-align:center;">
        <center>
          <i class="fa fa-plus fa-2x"></i>
        </center>
      </div>
  </div>
  </div>
</div>
