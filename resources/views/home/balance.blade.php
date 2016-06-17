<div ng-class="balance.class">
  <div class="panel-heading">
    <h3 class="panel-title">Balance</h3>
  </div>
  <div class="panel-body" style="font-size:30px; text-align:center;">
    <center>
      <span ng-style="{color:balance.color}" ng-bind="balance.total">Loading ...</span>
    </center>
  </div>

  <table class="table">
    <tr>
      <td>
        <center>
          <small>24 Hours : <span ng-bind-html="balance.trends[0]"></span></small>
          &nbsp;&nbsp;
          <small>1 Week : <span ng-bind-html="balance.trends[1]"></span></small>
          &nbsp;&nbsp;
          <small>1 Month : <span ng-bind-html="balance.trends[2]"></span></small>
        </center>
      </td>
    </tr>
  </table>

</div>
