<!-- Order View Modal -->
<div class="modal fade" id="orderViewModal" tabindex="-1" aria-labelledby="orderViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="orderViewModalLabel">Order Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Order Information</h6>
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Order ID:</div>
                                    <div class="col-7 order-id fw-bold"></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Date:</div>
                                    <div class="col-7 order-date"></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Status:</div>
                                    <div class="col-7 order-status"></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Total:</div>
                                    <div class="col-7 order-total fw-bold"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Customer Information</h6>
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Name:</div>
                                    <div class="col-7 customer-name"></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Email:</div>
                                    <div class="col-7 customer-email"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h6 class="text-muted mb-2">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsTable">
                            <!-- Order items will be inserted here dynamically -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td class="order-total-footer fw-bold"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary edit-order-btn" data-order-id="">Update Status</button>
            </div>
        </div>
    </div>
</div>

<!-- Order Status Update Modal -->
<div class="modal fade" id="orderStatusModal" tabindex="-1" aria-labelledby="orderStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="orderStatusModalLabel">Update Order Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateOrderStatusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="order-status-select" class="form-label">Status</label>
                        <select class="form-select" id="order-status-select" name="status">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status-notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="status-notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
