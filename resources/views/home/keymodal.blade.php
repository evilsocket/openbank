<div class="modal fade" tabindex="-1" role="dialog" id="keymodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="key_modal_title">Success</h4>
      </div>
      <div class="modal-body">

        <form class="form-horizontal" role="form" method="POST" action="#">

            <div class="form-group">
                <label for="key_label" class="col-md-4 control-label">Label</label>
                <div class="col-md-6">
                  <input type="text" name="key_label" class="form-control" id="key_label" value=""/>
                </div>
            </div>

            <div class="form-group">
                <label for="key_value" class="col-md-4 control-label">Key</label>
                <div class="col-md-6">
                  <input type="text" name="key_value" class="form-control" id="key_value" value=""/>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <button id="key_save" class="btn btn-success">
                      <i class="fa fa-btn fa-plus"></i> Save
                  </button>
                </div>
            </div>

        </form>

      </div>
    </div>
  </div>
</div>
