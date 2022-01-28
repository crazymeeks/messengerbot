<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal-mini__close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Updating state of order with reference number: <span id="modal-reference__number"></span></h4>
                <h3>Customer information:</h3>
                <div id="modal-order__info"></div>
            </div>
            <div class="modal-body">
                <select name="state" class="form-control" id="modal-order__state">
                    <option value="new order">New Order</option>
                    <option value="processing">Processing</option>
                    <option value="shipping">Shipping</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancel</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" id="modal-btn__close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="modal-btn__save">Save changes</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>