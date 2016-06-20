<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">BTC</h3>
  </div>
  <div class="panel-body" style="font-size:30px; text-align:center;">
    <span ng-bind="btc.total">Loading ...</span>
  </div>

  <table class="table">
    <tr>
      <td>
        <center>
          <small style="color:#aaa">Last transaction seen <span ng-bind="btc.timestamp"></span></small>
        </center>
      </td>
    </tr>
  </table>
</div>
