<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Current Price</h3>
  </div>
  <div class="panel-body" style="font-size:30px; text-align:center;">
    <center>
      <span ng-bind="price.current">Loading ...</span>
    </center>
  </div>

  <table class="table">
    <tr>
      <td>
        <center>
          <small>Updated <span ng-bind="price.timestamp"></span></small>
        </center>
      </td>
    </tr>
  </table>
</div>
