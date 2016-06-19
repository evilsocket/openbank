<div class="row">
  <div ng-repeat="key in keys" class="col-md-2">
  <div class="panel panel-default key_edit" style="cursor: pointer;" data-label="<% key.label %>" data-key="<% key.value %>">
      <div class="panel-heading">
        <span ng-bind="key.label"></span>
        <span style="float:right">
          <a href="#" class="btn btn-xs btn-danger key_delete" data-key="<% key.value %>">
            <i class="fa fa-trash"></i>
          </a>
        </span>
      </div>
      <div class="panel-body" style="font-size:25px;">
        <center>
          <span ng-bind="key.balance + ' ฿'">Loading ...</span>
          <br/>
          <span ng-bind="money( key.balance * price.raw, 2, currency.symbol )" style="font-size:15px; color: #aaa">Loading ...</span>
        </center>
      </div>
      <table class="table">
        <tr>
          <td>
            <center>
              <small style="color:#aaa; font-size:10px;">Last update <span ng-bind="key.updated_at"></span></small>
            </center>
          </td>
        </tr>
      </table>
  </div>
  </div>

  <div class="col-md-2">
  <div id="add_key" class="panel panel-default" style="padding: 37px; cursor: pointer; background-color:#c6c9ce; border-color: #b6b9be; color:white">
      <div class="panel-body" style="font-size:26px; text-align:center;">
        <center>
          <i class="fa fa-plus fa-2x"></i>
        </center>
      </div>
  </div>
  </div>
</div>
