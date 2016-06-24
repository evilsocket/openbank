<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Balance</h3>
  </div>
  <div class="panel-body" style="font-size:30px; text-align:center;">
    <center>
      <span ng-bind="balance.total">Loading ...</span>
    </center>
  </div>

  <table class="table">
    <tr>
      <td>
        <center>
          <small style="color:#aaa">24 Hours : <span ng-bind-html="balance.trends[0]"></span></small>
          &nbsp;&nbsp;
          <small style="color:#aaa">1 Week : <span ng-bind-html="balance.trends[1]"></span></small>
          &nbsp;&nbsp;
          <small style="color:#aaa">1 Month : <span ng-bind-html="balance.trends[2]"></span></small>
        </center>
      </td>
    </tr>
  </table>

</div>
